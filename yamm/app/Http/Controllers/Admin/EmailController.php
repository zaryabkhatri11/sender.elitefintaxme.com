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
