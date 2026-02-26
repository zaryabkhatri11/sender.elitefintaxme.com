<?php

namespace App\DataTables\Admin;

use App\Helper\Util;
use App\Models\Sheet;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class SheetDataTable
 * @package App\DataTables\Admin
 */
class SheetDataTable extends DataTable
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

        return $dataTable->editColumn('status', function ($sheet) {
            $class = $sheet->status == 'live' ? 'label-success' : 'label-danger';
            return '<span class="label ' . $class . '">' . ucfirst($sheet->status) . '</span>';
        })
            ->addColumn('action', 'admin.customers.datatables_actions') // Note: Sheets usually have their own actions but user screenshot shows similar
            ->rawColumns(['status', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Sheet $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Sheet $model)
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
        if (\Entrust::can('sheets.create') || \Entrust::hasRole('super-admin')) {
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
            'link',
            'status',
            'created_at'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'sheetsdatatable_' . time();
    }
}