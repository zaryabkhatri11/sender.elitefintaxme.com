<?php

namespace App\Http\Controllers\Admin;

use App\Helper\BreadcrumbsRegister;
use App\DataTables\Admin\InvoiceDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateInvoiceRequest;
use App\Http\Requests\Admin\UpdateInvoiceRequest;
use App\Repositories\Admin\InvoiceRepository;
use App\Http\Controllers\AppBaseController;
use Laracasts\Flash\Flash;
use Illuminate\Http\Response;

class InvoiceController extends AppBaseController
{
    /** ModelName */
    private $ModelName;

    /** BreadCrumbName */
    private $BreadCrumbName;

    /** @var  InvoiceRepository */
    private $invoiceRepository;

    public function __construct(InvoiceRepository $invoiceRepo)
    {
        $this->invoiceRepository = $invoiceRepo;
        $this->ModelName = 'invoices';
        $this->BreadCrumbName = 'Invoices';
    }

    /**
     * Display a listing of the Invoice.
     *
     * @param InvoiceDataTable $invoiceDataTable
     * @return Response
     */
    public function index(InvoiceDataTable $invoiceDataTable)
    {
        BreadcrumbsRegister::Register($this->ModelName,$this->BreadCrumbName);
        return $invoiceDataTable->render('admin.invoices.index', ['title' => $this->BreadCrumbName]);
    }

    /**
     * Show the form for creating a new Invoice.
     *
     * @return Response
     */
    public function create()
    {
        BreadcrumbsRegister::Register($this->ModelName,$this->BreadCrumbName);
        return view('admin.invoices.create')->with(['title' => $this->BreadCrumbName]);
    }

    /**
     * Store a newly created Invoice in storage.
     *
     * @param CreateInvoiceRequest $request
     *
     * @return Response
     */
    public function store(CreateInvoiceRequest $request)
    {
        $invoice = $this->invoiceRepository->saveRecord($request);

        Flash::success($this->BreadCrumbName . ' saved successfully.');
        if (isset($request->continue)) {
            $redirect_to = redirect(route('admin.invoices.create'));
        } elseif (isset($request->translation)) {
            $redirect_to = redirect(route('admin.invoices.edit', $invoice->id));
        } else {
            $redirect_to = redirect(route('admin.invoices.index'));
        }
        return $redirect_to->with([
            'title' => $this->BreadCrumbName
        ]);
    }

    /**
     * Display the specified Invoice.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $invoice = $this->invoiceRepository->findWithoutFail($id);

        if (empty($invoice)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.invoices.index'));
        }

        BreadcrumbsRegister::Register($this->ModelName,$this->BreadCrumbName, $invoice);
        return view('admin.invoices.show')->with(['invoice' => $invoice, 'title' => $this->BreadCrumbName]);
    }

    /**
     * Show the form for editing the specified Invoice.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $invoice = $this->invoiceRepository->findWithoutFail($id);

        if (empty($invoice)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.invoices.index'));
        }

        BreadcrumbsRegister::Register($this->ModelName,$this->BreadCrumbName, $invoice);
        return view('admin.invoices.edit')->with(['invoice' => $invoice, 'title' => $this->BreadCrumbName]);
    }

    /**
     * Update the specified Invoice in storage.
     *
     * @param  int              $id
     * @param UpdateInvoiceRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInvoiceRequest $request)
    {
        $invoice = $this->invoiceRepository->findWithoutFail($id);

        if (empty($invoice)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.invoices.index'));
        }

        $invoice = $this->invoiceRepository->updateRecord($request, $invoice);

        Flash::success($this->BreadCrumbName . ' updated successfully.');
        if (isset($request->continue)) {
            $redirect_to = redirect(route('admin.invoices.create'));
        } else {
            $redirect_to = redirect(route('admin.invoices.index'));
        }
        return $redirect_to->with(['title' => $this->BreadCrumbName]);
    }

    /**
     * Remove the specified Invoice from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $invoice = $this->invoiceRepository->findWithoutFail($id);

        if (empty($invoice)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.invoices.index'));
        }

        $this->invoiceRepository->deleteRecord($id);

        Flash::success($this->BreadCrumbName . ' deleted successfully.');
        return redirect(route('admin.invoices.index'))->with(['title' => $this->BreadCrumbName]);
    }
}
