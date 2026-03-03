<?php

namespace App\Http\Controllers\Admin;

use App\Helper\BreadcrumbsRegister;
use App\DataTables\Admin\CustomerDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateCustomerRequest;
use App\Http\Requests\Admin\UpdateCustomerRequest;
use App\Repositories\Admin\CustomerRepository;
use App\Http\Controllers\AppBaseController;
use Laracasts\Flash\Flash;
use Illuminate\Http\Response;
use App\Models\CustomerEmailLog;

class CustomerController extends AppBaseController
{
    /** ModelName */
    private $ModelName;

    /** BreadCrumbName */
    private $BreadCrumbName;

    /** @var  CustomerRepository */
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepo)
    {
        $this->customerRepository = $customerRepo;
        $this->ModelName = 'customers';
        $this->BreadCrumbName = 'Customers';
    }

    /**
     * Display a listing of the Customer.
     *
     * @param CustomerDataTable $customerDataTable
     * @return Response
     */
    public function index(CustomerDataTable $customerDataTable)
    {
        BreadcrumbsRegister::Register($this->ModelName, $this->BreadCrumbName);
        return $customerDataTable->render('admin.customers.index', ['title' => $this->BreadCrumbName]);
    }

    /**
     * Show the form for creating a new Customer.
     *
     * @return Response
     */
    public function create()
    {
        BreadcrumbsRegister::Register($this->ModelName, $this->BreadCrumbName);
        return view('admin.customers.create')->with(['title' => $this->BreadCrumbName]);
    }

    /**
     * Store a newly created Customer in storage.
     *
     * @param CreateCustomerRequest $request
     *
     * @return Response
     */
    public function store(CreateCustomerRequest $request)
    {
        $customer = $this->customerRepository->saveRecord($request);

        Flash::success($this->BreadCrumbName . ' saved successfully.');
        if (isset($request->continue)) {
            $redirect_to = redirect(route('admin.customers.create'));
        } elseif (isset($request->translation)) {
            $redirect_to = redirect(route('admin.customers.edit', $customer->id));
        } else {
            $redirect_to = redirect(route('admin.customers.index'));
        }
        return $redirect_to->with([
            'title' => $this->BreadCrumbName
        ]);
    }

    /**
     * Display the specified Customer.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $customer = $this->customerRepository->findWithoutFail($id);

        if (empty($customer)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.customers.index'));
        }

        BreadcrumbsRegister::Register($this->ModelName, $this->BreadCrumbName, $customer);
        return view('admin.customers.show')->with(['customer' => $customer, 'title' => $this->BreadCrumbName]);
    }

    /**
     * Show the form for editing the specified Customer.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $customer = $this->customerRepository->findWithoutFail($id);

        if (empty($customer)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.customers.index'));
        }

        BreadcrumbsRegister::Register($this->ModelName, $this->BreadCrumbName, $customer);
        return view('admin.customers.edit')->with(['customer' => $customer, 'title' => $this->BreadCrumbName]);
    }

    /**
     * Update the specified Customer in storage.
     *
     * @param  int              $id
     * @param UpdateCustomerRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCustomerRequest $request)
    {
        $customer = $this->customerRepository->findWithoutFail($id);

        if (empty($customer)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.customers.index'));
        }

        $customer = $this->customerRepository->updateRecord($request, $customer);

        Flash::success($this->BreadCrumbName . ' updated successfully.');
        if (isset($request->continue)) {
            $redirect_to = redirect(route('admin.customers.create'));
        } else {
            $redirect_to = redirect(route('admin.customers.index'));
        }
        return $redirect_to->with(['title' => $this->BreadCrumbName]);
    }

    /**
     * Remove the specified Customer from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $customer = $this->customerRepository->findWithoutFail($id);

        if (empty($customer)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.customers.index'));
        }

        $this->customerRepository->deleteRecord($id);

        Flash::success($this->BreadCrumbName . ' deleted successfully.');
        return redirect(route('admin.customers.index'))->with(['title' => $this->BreadCrumbName]);
    }

    /**
     * Display the specified Customer's email history.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function emails($id)
    {
        return $this->threads($id);
    }

    /**
     * Display unique threads for a customer.
     */
    public function threads($id)
    {
        $customer = $this->customerRepository->findWithoutFail($id);

        if (empty($customer)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.customers.index'));
        }

        // Group by thread_id and get the latest message for each thread
        $threads = CustomerEmailLog::where('customer_id', $id)
            ->whereNotNull('thread_id')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('thread_id');

        BreadcrumbsRegister::Register($this->ModelName, $this->BreadCrumbName, $customer);
        return view('admin.customers.threads')->with([
            'customer' => $customer,
            'threads' => $threads,
            'title' => $this->BreadCrumbName . ' Conversations'
        ]);
    }

    /**
     * Display messages for a specific thread.
     */
    public function threadDetail($id, $thread_id)
    {
        $customer = $this->customerRepository->findWithoutFail($id);

        if (empty($customer)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.customers.index'));
        }

        $emails = CustomerEmailLog::where('customer_id', $id)
            ->where('thread_id', $thread_id)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($emails->isEmpty()) {
            Flash::error('Thread not found');
            return redirect(route('admin.customers.threads', $id));
        }

        BreadcrumbsRegister::Register($this->ModelName, $this->BreadCrumbName, $customer);
        return view('admin.customers.emails')->with([
            'customer' => $customer,
            'emails' => $emails,
            'thread_id' => $thread_id,
            'title' => 'Thread: ' . ($emails->first()->subject ?: 'No Subject')
        ]);
    }

    /**
     * Send a direct email to the customer and log it.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmail($id, \Illuminate\Http\Request $request)
    {
        $customer = $this->customerRepository->findWithoutFail($id);

        if (empty($customer)) {
            return response()->json(['success' => false, 'message' => 'Customer not found.'], 404);
        }

        $messageText = $request->input('message');
        if (empty($messageText)) {
            return response()->json(['success' => false, 'message' => 'Message cannot be empty.'], 422);
        }

        $subject = $request->input('subject', 'Follow up - ' . config('app.name'));
        $attachment = $request->input('attachment');
        $attachmentPath = null;

        if (!empty($attachment)) {
            $attachmentPath = public_path('attachments/' . basename($attachment));
            if (!file_exists($attachmentPath)) {
                return response()->json(['success' => false, 'message' => 'Attachment file not found.'], 404);
            }
        }

        $messageId = null;

        try {
            \Illuminate\Support\Facades\Mail::raw($messageText, function ($mail) use ($customer, $subject, &$messageId, $attachmentPath) {
                $mail->to($customer->email, $customer->owner_name)
                    ->subject($subject);

                if ($attachmentPath) {
                    $mail->attach($attachmentPath);
                }

                $messageId = $mail->getId();
            });

            $log = CustomerEmailLog::create([
                'customer_id' => $customer->id,
                'direction' => 'outgoing',
                'from_email' => config('mail.from.address'),
                'to_email' => $customer->email,
                'subject' => $subject,
                'message' => $messageText,
                'attachment' => $attachment,
                'message_id' => $messageId,
                'in_reply_to' => $request->input('in_reply_to'),
                'thread_id' => $request->input('thread_id') ?: $messageId,
                'status' => 'sent'
            ]);

            $html = view('admin.customers.email_bubble', [
                'email' => $log,
                'isIncoming' => false
            ])->render();

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully!',
                'html' => $html,
                'log' => [
                    'direction' => $log->direction,
                    'from' => $log->from_email,
                    'subject' => $log->subject,
                    'body' => $log->message,
                    'time' => $log->created_at->format('M d, Y h:i A'),
                    'status' => $log->status
                ]
            ]);

        } catch (\Exception $e) {
            CustomerEmailLog::create([
                'customer_id' => $customer->id,
                'direction' => 'outgoing',
                'from_email' => config('mail.from.address'),
                'to_email' => $customer->email,
                'subject' => $subject,
                'message' => $messageText,
                'status' => 'failed',
                'error' => $e->getMessage()
            ]);

            return response()->json(['success' => false, 'message' => 'Failed to send email: ' . $e->getMessage()], 500);
        }
    }
}
