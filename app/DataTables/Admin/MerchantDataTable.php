<?php

namespace App\DataTables\Admin;

use App\Helper\Util;
use App\Models\Merchant;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class MerchantDataTable
 * @package App\DataTables\Admin
 */
class MerchantDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable
            ->editColumn('payment_account_id', function ($model) {
                return $model->paymentAccount ? $model->paymentAccount->name : $model->payment_account_id;
            })
            ->editColumn('payment_link', function ($model) {
                if ($model->payment_link) {
                    return '<a href="' . $model->payment_link . '" target="_blank" class="btn btn-xs btn-success">View Invoice</a>';
                }
                return '<span class="label label-default">N/A</span>';
            })
            ->rawColumns(['payment_link', 'action'])
            ->addColumn('action', 'admin.merchants.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Merchant $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Merchant $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $buttons = [];
        if (\Entrust::can('merchants.create') || \Entrust::hasRole('super-admin')) {
            $buttons = ['create'];
        }
        $buttons = array_merge($buttons, [
            'export',
            'print',
            'reset',
            'reload',
        ]);
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '80px', 'printable' => false])
            ->parameters(array_merge(Util::getDataTableParams(), [
                'dom' => 'Blfrtip',
                'order' => [[0, 'desc']],
                'buttons' => $buttons,
            ]));
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id',
            'name',
            'email',
            'payment_account_id' => ['title' => 'Payment Account'],
            'payment_link' => ['title' => 'Payment Link'],
            'created_at',
            'updated_at'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'merchantsdatatable_' . time();
    }
}