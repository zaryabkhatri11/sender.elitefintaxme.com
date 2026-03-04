<?php

namespace App\Http\Controllers\Admin;

use App\Helper\BreadcrumbsRegister;
use App\DataTables\Admin\PaymentAccountDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreatePaymentAccountRequest;
use App\Http\Requests\Admin\UpdatePaymentAccountRequest;
use App\Repositories\Admin\PaymentAccountRepository;
use App\Http\Controllers\AppBaseController;
use Laracasts\Flash\Flash;
use Illuminate\Http\Response;

class PaymentAccountController extends AppBaseController
{
    /** ModelName */
    private $ModelName;

    /** BreadCrumbName */
    private $BreadCrumbName;

    /** @var  PaymentAccountRepository */
    private $paymentAccountRepository;

    public function __construct(PaymentAccountRepository $paymentAccountRepo)
    {
        $this->paymentAccountRepository = $paymentAccountRepo;
        $this->ModelName = 'payment-accounts';
        $this->BreadCrumbName = 'Payment Accounts';
    }

    /**
     * Display a listing of the PaymentAccount.
     *
     * @param PaymentAccountDataTable $paymentAccountDataTable
     * @return Response
     */
    public function index(PaymentAccountDataTable $paymentAccountDataTable)
    {
        BreadcrumbsRegister::Register($this->ModelName,$this->BreadCrumbName);
        return $paymentAccountDataTable->render('admin.payment_accounts.index', ['title' => $this->BreadCrumbName]);
    }

    /**
     * Show the form for creating a new PaymentAccount.
     *
     * @return Response
     */
    public function create()
    {
        BreadcrumbsRegister::Register($this->ModelName,$this->BreadCrumbName);
        return view('admin.payment_accounts.create')->with(['title' => $this->BreadCrumbName]);
    }

    /**
     * Store a newly created PaymentAccount in storage.
     *
     * @param CreatePaymentAccountRequest $request
     *
     * @return Response
     */
    public function store(CreatePaymentAccountRequest $request)
    {
        $paymentAccount = $this->paymentAccountRepository->saveRecord($request);

        Flash::success($this->BreadCrumbName . ' saved successfully.');
        if (isset($request->continue)) {
            $redirect_to = redirect(route('admin.payment-accounts.create'));
        } elseif (isset($request->translation)) {
            $redirect_to = redirect(route('admin.payment-accounts.edit', $paymentAccount->id));
        } else {
            $redirect_to = redirect(route('admin.payment-accounts.index'));
        }
        return $redirect_to->with([
            'title' => $this->BreadCrumbName
        ]);
    }

    /**
     * Display the specified PaymentAccount.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $paymentAccount = $this->paymentAccountRepository->findWithoutFail($id);

        if (empty($paymentAccount)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.payment-accounts.index'));
        }

        BreadcrumbsRegister::Register($this->ModelName,$this->BreadCrumbName, $paymentAccount);
        return view('admin.payment_accounts.show')->with(['paymentAccount' => $paymentAccount, 'title' => $this->BreadCrumbName]);
    }

    /**
     * Show the form for editing the specified PaymentAccount.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $paymentAccount = $this->paymentAccountRepository->findWithoutFail($id);

        if (empty($paymentAccount)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.payment-accounts.index'));
        }

        BreadcrumbsRegister::Register($this->ModelName,$this->BreadCrumbName, $paymentAccount);
        return view('admin.payment_accounts.edit')->with(['paymentAccount' => $paymentAccount, 'title' => $this->BreadCrumbName]);
    }

    /**
     * Update the specified PaymentAccount in storage.
     *
     * @param  int              $id
     * @param UpdatePaymentAccountRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePaymentAccountRequest $request)
    {
        $paymentAccount = $this->paymentAccountRepository->findWithoutFail($id);

        if (empty($paymentAccount)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.payment-accounts.index'));
        }

        $paymentAccount = $this->paymentAccountRepository->updateRecord($request, $paymentAccount);

        Flash::success($this->BreadCrumbName . ' updated successfully.');
        if (isset($request->continue)) {
            $redirect_to = redirect(route('admin.payment-accounts.create'));
        } else {
            $redirect_to = redirect(route('admin.payment-accounts.index'));
        }
        return $redirect_to->with(['title' => $this->BreadCrumbName]);
    }

    /**
     * Remove the specified PaymentAccount from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $paymentAccount = $this->paymentAccountRepository->findWithoutFail($id);

        if (empty($paymentAccount)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.payment-accounts.index'));
        }

        $this->paymentAccountRepository->deleteRecord($id);

        Flash::success($this->BreadCrumbName . ' deleted successfully.');
        return redirect(route('admin.payment-accounts.index'))->with(['title' => $this->BreadCrumbName]);
    }
}
