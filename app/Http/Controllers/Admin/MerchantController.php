<?php

namespace App\Http\Controllers\Admin;

use App\Helper\BreadcrumbsRegister;
use App\DataTables\Admin\MerchantDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateMerchantRequest;
use App\Http\Requests\Admin\UpdateMerchantRequest;
use App\Repositories\Admin\MerchantRepository;
use App\Http\Controllers\AppBaseController;
use App\Models\PaymentAccount;
use Laracasts\Flash\Flash;
use Illuminate\Http\Response;

class MerchantController extends AppBaseController
{
    /** ModelName */
    private $ModelName;

    /** BreadCrumbName */
    private $BreadCrumbName;

    /** @var  MerchantRepository */
    private $merchantRepository;

    public function __construct(MerchantRepository $merchantRepo)
    {
        $this->merchantRepository = $merchantRepo;
        $this->ModelName = 'merchants';
        $this->BreadCrumbName = 'Merchants';
    }

    /**
     * Display a listing of the Merchant.
     *
     * @param MerchantDataTable $merchantDataTable
     * @return Response
     */
    public function index(MerchantDataTable $merchantDataTable)
    {
        BreadcrumbsRegister::Register($this->ModelName, $this->BreadCrumbName);
        return $merchantDataTable->render('admin.merchants.index', ['title' => $this->BreadCrumbName]);
    }

    /**
     * Show the form for creating a new Merchant.
     *
     * @return Response
     */
    public function create()
    {
        BreadcrumbsRegister::Register($this->ModelName, $this->BreadCrumbName);
        $paymentAccounts = PaymentAccount::where('is_active', 1)->pluck('name', 'id');
        return view('admin.merchants.create')->with(['title' => $this->BreadCrumbName, 'paymentAccounts' => $paymentAccounts]);
    }

    /**
     * Store a newly created Merchant in storage.
     *
     * @param CreateMerchantRequest $request
     *
     * @return Response
     */
    public function store(CreateMerchantRequest $request)
    {
        $merchant = $this->merchantRepository->saveRecord($request);

        Flash::success($this->BreadCrumbName . ' saved successfully.');
        if (isset($request->continue)) {
            $redirect_to = redirect(route('admin.merchants.create'));
        } elseif (isset($request->translation)) {
            $redirect_to = redirect(route('admin.merchants.edit', $merchant->id));
        } else {
            $redirect_to = redirect(route('admin.merchants.index'));
        }
        return $redirect_to->with([
            'title' => $this->BreadCrumbName
        ]);
    }

    /**
     * Display the specified Merchant.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $merchant = $this->merchantRepository->findWithoutFail($id);

        if (empty($merchant)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.merchants.index'));
        }

        BreadcrumbsRegister::Register($this->ModelName, $this->BreadCrumbName, $merchant);
        return view('admin.merchants.show')->with(['merchant' => $merchant, 'title' => $this->BreadCrumbName]);
    }

    /**
     * Show the form for editing the specified Merchant.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $merchant = $this->merchantRepository->findWithoutFail($id);

        if (empty($merchant)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.merchants.index'));
        }

        BreadcrumbsRegister::Register($this->ModelName, $this->BreadCrumbName, $merchant);
        $paymentAccounts = PaymentAccount::where('is_active', 1)->pluck('name', 'id');
        return view('admin.merchants.edit')->with(['merchant' => $merchant, 'title' => $this->BreadCrumbName, 'paymentAccounts' => $paymentAccounts]);
    }

    /**
     * Update the specified Merchant in storage.
     *
     * @param  int              $id
     * @param UpdateMerchantRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMerchantRequest $request)
    {
        $merchant = $this->merchantRepository->findWithoutFail($id);

        if (empty($merchant)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.merchants.index'));
        }

        $merchant = $this->merchantRepository->updateRecord($request, $merchant);

        Flash::success($this->BreadCrumbName . ' updated successfully.');
        if (isset($request->continue)) {
            $redirect_to = redirect(route('admin.merchants.create'));
        } else {
            $redirect_to = redirect(route('admin.merchants.index'));
        }
        return $redirect_to->with(['title' => $this->BreadCrumbName]);
    }

    /**
     * Remove the specified Merchant from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $merchant = $this->merchantRepository->findWithoutFail($id);

        if (empty($merchant)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.merchants.index'));
        }

        $this->merchantRepository->deleteRecord($id);

        Flash::success($this->BreadCrumbName . ' deleted successfully.');
        return redirect(route('admin.merchants.index'))->with(['title' => $this->BreadCrumbName]);
    }
}
