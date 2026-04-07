<?php

namespace App\Http\Controllers\Admin;

use App\Helper\BreadcrumbsRegister;
use App\DataTables\Admin\MessagesLogDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateMessagesLogRequest;
use App\Http\Requests\Admin\UpdateMessagesLogRequest;
use App\Repositories\Admin\MessagesLogRepository;
use App\Http\Controllers\AppBaseController;
use Laracasts\Flash\Flash;
use Illuminate\Http\Response;
use App\Models\MessagesLog;
use App\Models\CallLog;
use App\Services\TwilioService;

class MessagesLogController extends AppBaseController
{
    /** ModelName */
    private $ModelName;

    /** BreadCrumbName */
    private $BreadCrumbName;

    /** @var  MessagesLogRepository */
    private $messagesLogRepository;

    public function __construct(MessagesLogRepository $messagesLogRepo)
    {
        $this->messagesLogRepository = $messagesLogRepo;
        $this->ModelName = 'messages-logs';
        $this->BreadCrumbName = 'Messages Logs';
    }

    /**
     * Display a listing of the MessagesLog.
     *
     * @param MessagesLogDataTable $messagesLogDataTable
     * @return Response
     */
    public function index()
    {
        BreadcrumbsRegister::Register($this->ModelName, $this->BreadCrumbName);

        // Group by customer_id if present, otherwise by the phone number
        $threads = \App\Models\MessagesLog::with('customer')
            ->whereIn('id', function ($query) {
                $query->select(\DB::raw('MAX(id)'))
                    ->from('sms_logs')
                    ->whereNull('deleted_at')
                    ->groupBy(\DB::raw('CASE WHEN customer_id IS NOT NULL THEN CAST(customer_id AS CHAR) ELSE to_num END'));
            })
            ->orderBy('id', 'desc')
            ->get();

        foreach ($threads as $thread) {
            $unreadQuery = \App\Models\MessagesLog::where('is_read', false)
                ->where('direction', 'inbound');

            if ($thread->customer_id) {
                $unreadQuery->where('customer_id', $thread->customer_id);
            } else {
                $phone = ($thread->direction == 'outbound') ? $thread->to_num : $thread->from_num;
                $unreadQuery->whereNull('customer_id')
                    ->where(function ($q) use ($phone) {
                        $q->where('to_num', $phone)->orWhere('from_num', $phone);
                    });
            }
            $thread->unread_count = $unreadQuery->count();
        }

        $callLogs = \App\Models\CallLog::with('customer')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.messages_logs.index', [
            'threads' => $threads,
            'callLogs' => $callLogs,
            'title' => $this->BreadCrumbName
        ]);
    }

    /**
     * Show the form for creating a new MessagesLog.
     *
     * @return Response
     */
    public function create()
    {
        BreadcrumbsRegister::Register($this->ModelName, $this->BreadCrumbName);
        $customers = \App\Models\Customer::orderBy('owner_name')->get();
        return view('admin.messages_logs.create')->with([
            'title' => $this->BreadCrumbName,
            'customers' => $customers
        ]);
    }

    /**
     * Store a newly created MessagesLog in storage.
     *
     * @param CreateMessagesLogRequest $request
     *
     * @return Response
     */
    public function store(CreateMessagesLogRequest $request, TwilioService $twilio)
    {
        $recipients = $request->to_num;
        if (!is_array($recipients)) {
            $recipients = [$recipients];
        }

        // Clean up recipients
        $recipients = array_filter(array_map('trim', $recipients));

        $customer_ids = $request->customer_id;
        if (!is_array($customer_ids)) {
            $customer_ids = array_filter([$customer_ids]);
        }

        // Create a map of phone => customer_id for selected customers
        $customerMap = [];
        if (!empty($customer_ids)) {
            $selectedCustomers = \App\Models\Customer::whereIn('id', $customer_ids)->get();
            foreach ($selectedCustomers as $c) {
                if ($c->phone) {
                    $customerMap[trim($c->phone)] = $c->id;
                }
            }
        }

        foreach ($recipients as $recipient) {
            $data = $request->all();
            $data['to_num'] = $recipient;
            $data['from_num'] = config('services.twilio.from');
            
            // Try to find matching customer by phone among selected ones
            // If not found, it remains null (direct number)
            $data['customer_id'] = $customerMap[$recipient] ?? null;
            $data['user_id'] = \Auth::id();
            
            // Send live SMS via Twilio if it's an outbound message
            if (($data['direction'] ?? 'outbound') == 'outbound') {
                $statusCallback = route('api.webhooks.twilio.status');
                $twilioResults = $twilio->sendSms($recipient, $data['body'], $statusCallback);
                if (!empty($twilioResults) && $twilioResults[0]['success']) {
                    $data['message_sid'] = $twilioResults[0]['sid'];
                    $data['status'] = $twilioResults[0]['status'];
                } else {
                    $data['status'] = 'failed';
                    $errorMsg = $twilioResults[0]['error'] ?? 'Unknown Twilio error';
                    \Log::error("Twilio SMS failed to $recipient: " . $errorMsg);
                    Flash::error("Failed to send SMS to $recipient: " . $errorMsg);
                }
            }

            // Log/Create the record
            $this->messagesLogRepository->create($data);
        }

        Flash::success($this->BreadCrumbName . ' saved and sent successfully.');
        if (isset($request->continue)) {
            $redirect_to = redirect(route('admin.messages-logs.create'));
        }
        else {
            $redirect_to = redirect(route('admin.messages-logs.index'));
        }
        return $redirect_to->with([
            'title' => $this->BreadCrumbName
        ]);
    }

    /**
     * Display the specified MessagesLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $messagesLog = $this->messagesLogRepository->findWithoutFail($id);

        if (empty($messagesLog)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.messages-logs.index'));
        }

        if ($messagesLog->customer_id) {
            $messages = \App\Models\MessagesLog::where('customer_id', $messagesLog->customer_id)
                ->get();
            $calls = \App\Models\CallLog::where('customer_id', $messagesLog->customer_id)
                ->get();

            // Mark as read
            \App\Models\MessagesLog::where('customer_id', $messagesLog->customer_id)
                ->where('direction', 'inbound')
                ->where('is_read', false)
                ->update(['is_read' => true]);
        } else {
            // Match by phone if no customer is linked
            $phone = ($messagesLog->direction == 'outbound') ? $messagesLog->to_num : $messagesLog->from_num;
            $messages = \App\Models\MessagesLog::where(function($q) use ($phone) {
                    $q->where('to_num', $phone)->orWhere('from_num', $phone);
                })
                ->whereNull('customer_id')
                ->get();
            $calls = \App\Models\CallLog::where(function($q) use ($phone) {
                    $q->where('to_num', $phone)->orWhere('from_num', $phone);
                })
                ->whereNull('customer_id')
                ->get();

            // Mark as read
            \App\Models\MessagesLog::whereNull('customer_id')
                ->where(function($q) use ($phone) {
                    $q->where('to_num', $phone)->orWhere('from_num', $phone);
                })
                ->where('direction', 'inbound')
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        // Merge and sort chronologically
        $combinedTimeline = $messages->concat($calls)->sortBy('created_at');

        BreadcrumbsRegister::Register($this->ModelName, $this->BreadCrumbName, $messagesLog);
        return view('admin.messages_logs.show')->with([
            'messagesLog' => $messagesLog,
            'messages' => $combinedTimeline,
            'title' => $this->BreadCrumbName
        ]);
    }

    /**
     * Show the form for editing the specified MessagesLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $messagesLog = $this->messagesLogRepository->findWithoutFail($id);

        if (empty($messagesLog)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.messages-logs.index'));
        }

        BreadcrumbsRegister::Register($this->ModelName, $this->BreadCrumbName, $messagesLog);
        return view('admin.messages_logs.edit')->with(['messagesLog' => $messagesLog, 'title' => $this->BreadCrumbName]);
    }

    /**
     * Update the specified MessagesLog in storage.
     *
     * @param  int              $id
     * @param UpdateMessagesLogRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMessagesLogRequest $request)
    {
        $messagesLog = $this->messagesLogRepository->findWithoutFail($id);

        if (empty($messagesLog)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.messages-logs.index'));
        }

        $messagesLog = $this->messagesLogRepository->updateRecord($request, $messagesLog);

        Flash::success($this->BreadCrumbName . ' updated successfully.');
        if (isset($request->continue)) {
            $redirect_to = redirect(route('admin.messages-logs.create'));
        }
        else {
            $redirect_to = redirect(route('admin.messages-logs.index'));
        }
        return $redirect_to->with(['title' => $this->BreadCrumbName]);
    }

    /**
     * Remove the specified MessagesLog from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $messagesLog = $this->messagesLogRepository->findWithoutFail($id);

        if (empty($messagesLog)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.messages-logs.index'));
        }

        $this->messagesLogRepository->deleteRecord($id);

        Flash::success($this->BreadCrumbName . ' deleted successfully.');
        return redirect(route('admin.messages-logs.index'))->with(['title' => $this->BreadCrumbName]);
    }

    /**
     * Initiate a voice call via Twilio.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function initiateCall(\Illuminate\Http\Request $request)
    {
        $to = $request->input('to_num');
        $adminPhone = $request->input('admin_phone');
        $customerId = $request->input('customer_id');
        
        if (!$adminPhone) {
            return response()->json(['success' => false, 'message' => 'Please provide your phone number first.']);
        }

        $twilio = new TwilioService();
        // Force HTTPS for the bridge URL to ensure Twilio can reach it securely
        $bridgeUrl = route('api.webhooks.twilio.bridge-voice', ['customer_num' => $to]);
        if (strpos($bridgeUrl, 'http://') === 0) {
            $bridgeUrl = str_replace('http://', 'https://', $bridgeUrl);
        }
        $statusCallback = route('api.webhooks.twilio.call-status');
        if (strpos($statusCallback, 'http://') === 0) {
            $statusCallback = str_replace('http://', 'https://', $statusCallback);
        }

        \Log::info("Initiating Bridge Call: Admin=$adminPhone -> Customer=$to via BridgeUrl=$bridgeUrl");

        // Bridge: Call Admin -> When answered -> Connect to Customer
        $result = $twilio->bridgeCall($adminPhone, $to, $bridgeUrl, $statusCallback);

        if ($result['success']) {
            CallLog::create([
                'user_id' => \Auth::id(),
                'customer_id' => $customerId,
                'from_num' => $adminPhone, // Leg 1: Admin
                'to_num' => $to,           // Leg 2: Customer
                'call_sid' => $result['sid'],
                'status' => $result['status']
            ]);

            return response()->json(['success' => true, 'message' => 'Calling your phone now... Please answer to be connected.']);
        }

        $errorMessage = $result['error'] ?? ($result['message'] ?? 'Unknown error');
        return response()->json(['success' => false, 'message' => 'Failed to initiate call: ' . $errorMessage]);
    }
}
