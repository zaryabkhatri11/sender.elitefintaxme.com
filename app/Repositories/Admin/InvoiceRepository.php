<?php

namespace App\Repositories\Admin;

use App\Models\Invoice;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class InvoiceRepository
 * @package App\Repositories\Admin
 * @version March 3, 2026, 10:59 pm UTC
 *
 * @method Invoice findWithoutFail($id, $columns = ['*'])
 * @method Invoice find($id, $columns = ['*'])
 * @method Invoice first($columns = ['*'])
*/
class InvoiceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'merchant_id',
        'uuid',
        'amount',
        'currency',
        'description',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Invoice::class;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function saveRecord($request)
    {
        $input = $request->all();
        $invoice = $this->create($input);
        return $invoice;
    }

    /**
     * @param $request
     * @param $invoice
     * @return mixed
     */
    public function updateRecord($request, $invoice)
    {
        $input = $request->all();
        $invoice = $this->update($input, $invoice->id);
        return $invoice;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteRecord($id)
    {
        $invoice = $this->delete($id);
        return $invoice;
    }
}
