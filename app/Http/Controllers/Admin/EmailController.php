<?php

namespace App\Http\Controllers\Admin;

use App\Helper\BreadcrumbsRegister;
use App\DataTables\Admin\EmailDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateEmailRequest;
use App\Http\Requests\Admin\UpdateEmailRequest;
use App\Repositories\Admin\EmailRepository;
use App\Http\Controllers\AppBaseController;
use Laracasts\Flash\Flash;
use Illuminate\Http\Response;

use Illuminate\Http\Request;
use App\Jobs\SendEmailJob;
use App\Models\EmailLog;

class EmailController extends AppBaseController
{
    /** ModelName */
    private $ModelName;

    /** BreadCrumbName */
    private $BreadCrumbName;

    /** @var  EmailRepository */
    private $emailRepository;

    public function __construct(EmailRepository $emailRepo)
    {
        $this->emailRepository = $emailRepo;
        $this->ModelName = 'emails';
        $this->BreadCrumbName = 'Emails';
    }

    /**
     * Display a listing of the Email.
     *
     * @param EmailDataTable $emailDataTable
     * @return Response
     */
    public function index(EmailDataTable $emailDataTable)
    {
        BreadcrumbsRegister::Register($this->ModelName,$this->BreadCrumbName);
        return $emailDataTable->render('admin.emails.index', ['title' => $this->BreadCrumbName]);
    }

    /**
     * Show the form for creating a new Email.
     *
     * @return Response
     */
    public function create()
    {
        BreadcrumbsRegister::Register($this->ModelName,$this->BreadCrumbName);
        return view('admin.emails.create')->with(['title' => $this->BreadCrumbName]);
    }

    /**
     * Store a newly created Email in storage.
     *
     * @param CreateEmailRequest $request
     *
     * @return Response
     */
    public function store(CreateEmailRequest $request)
    {
        $email = $this->emailRepository->saveRecord($request);

        Flash::success($this->BreadCrumbName . ' saved successfully.');
        if (isset($request->continue)) {
            $redirect_to = redirect(route('admin.emails.create'));
        } elseif (isset($request->translation)) {
            $redirect_to = redirect(route('admin.emails.edit', $email->id));
        } else {
            $redirect_to = redirect(route('admin.emails.index'));
        }
        return $redirect_to->with([
            'title' => $this->BreadCrumbName
        ]);
    }

    /**
     * Display the specified Email.
     *
     * @param  int $id
     *
     * @return Response
     */
public function sendEmails(Request $request)
{
    $emails = explode(',', $request->emails);

    foreach ($emails as $email) {
        $email = trim($email);
        //if(!$email) continue;

//         SendEmailJob::dispatch([
//     'email'   => $email,
//     'name'    => $row[2],
//     'entity'  => $row[3],
//     'address' => $row[4],
//     'wordmark'=> $row[5],
//     'serial'  => $row[6],
// 'date'    => date('l, F d, Y', strtotime('+1 day')), // ✅ MUST
//     // ✅ from form inputs (same for whole batch)
//     'examining_attorney' => $request->examining_attorney,
//     'direct_phone'       => $request->direct_phone,
//     'appointment_date'   => $request->appointment_date,
//     'appointment_time'   => $request->appointment_time,
//     'appointment_number' => $request->appointment_number,
// ])->onQueue('emails');

    }

    return response()->json([
        'status' => true,
        'total'  => count($emails)
    ]);
}
public function progress(Request $request)
{
    $total  = (int) $request->get('total', 0);
    $lastId = (int) $request->get('last_id', 0);

    $success = EmailLog::where('status','success')->count();
    $failed  = EmailLog::where('status','failed')->count();
    $sent    = $success + $failed;

    // ✅ only new rows since last_id
    $logs = EmailLog::where('id', '>', $lastId)
        ->orderBy('id', 'asc')
        ->limit(200)
        ->get(['id','email','status','error','created_at']);

    $newLastId = $lastId;
    if ($logs->count()) {
        $newLastId = $logs->last()->id;
    }

    return response()->json([
        'sent'    => $sent,
        'success' => $success,
        'failed'  => $failed,
        'total'   => $total,
        'last_id' => $newLastId,
        'logs'    => $logs,
    ]);
}


public function retryFailed()
{
    $failed = EmailLog::where('status','failed')->pluck('email');

    foreach ($failed as $email) {
        SendEmailJob::dispatch([
    'email'   => $email,
    'name'    => $row[2],
    'entity'  => $row[3],
    'address' => $row[4],
    'wordmark'=> $row[5],
    'serial'  => $row[6],
    'date'    => date('l, F d, Y', strtotime('+1 day')), // ✅ MUST
    // ✅ from form inputs (same for whole batch)
    'examining_attorney' => $request->examining_attorney,
    'direct_phone'       => $request->direct_phone,
    'appointment_date'   => $request->appointment_date,
    'appointment_time'   => $request->appointment_time,
    'appointment_number' => $request->appointment_number,
])->onQueue('emails');

    }

    return back()->with('success','Retry started');
}



    public function show($id)
    {
        $email = $this->emailRepository->findWithoutFail($id);

        if (empty($email)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.emails.index'));
        }

        BreadcrumbsRegister::Register($this->ModelName,$this->BreadCrumbName, $email);
        return view('admin.emails.show')->with(['email' => $email, 'title' => $this->BreadCrumbName]);
    }

    /**
     * Show the form for editing the specified Email.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $email = $this->emailRepository->findWithoutFail($id);

        if (empty($email)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.emails.index'));
        }

        BreadcrumbsRegister::Register($this->ModelName,$this->BreadCrumbName, $email);
        return view('admin.emails.edit')->with(['email' => $email, 'title' => $this->BreadCrumbName]);
    }

    /**
     * Update the specified Email in storage.
     *
     * @param  int              $id
     * @param UpdateEmailRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEmailRequest $request)
    {
        $email = $this->emailRepository->findWithoutFail($id);

        if (empty($email)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.emails.index'));
        }

        $email = $this->emailRepository->updateRecord($request, $email);

        Flash::success($this->BreadCrumbName . ' updated successfully.');
        if (isset($request->continue)) {
            $redirect_to = redirect(route('admin.emails.create'));
        } else {
            $redirect_to = redirect(route('admin.emails.index'));
        }
        return $redirect_to->with(['title' => $this->BreadCrumbName]);
    }

    /**
     * Remove the specified Email from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $email = $this->emailRepository->findWithoutFail($id);

        if (empty($email)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.emails.index'));
        }

        $this->emailRepository->deleteRecord($id);

        Flash::success($this->BreadCrumbName . ' deleted successfully.');
        return redirect(route('admin.emails.index'))->with(['title' => $this->BreadCrumbName]);
    }
}
