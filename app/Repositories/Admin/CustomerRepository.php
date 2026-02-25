<?php

namespace App\Repositories\Admin;

use App\Models\Customer;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class CustomerRepository
 * @package App\Repositories\Admin
 * @version February 24, 2026, 11:48 pm UTC
 *
 * @method Customer findWithoutFail($id, $columns = ['*'])
 * @method Customer find($id, $columns = ['*'])
 * @method Customer first($columns = ['*'])
*/
class CustomerRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'email',
        'phone',
        'owner_name',
        'entity',
        'owner_address',
        'subject_mark',
        'case_number'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Customer::class;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function saveRecord($request)
    {
        $input = $request->all();
        $customer = $this->create($input);
        return $customer;
    }

    /**
     * @param $request
     * @param $customer
     * @return mixed
     */
    public function updateRecord($request, $customer)
    {
        $input = $request->all();
        $customer = $this->update($input, $customer->id);
        return $customer;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteRecord($id)
    {
        $customer = $this->delete($id);
        return $customer;
    }
}
