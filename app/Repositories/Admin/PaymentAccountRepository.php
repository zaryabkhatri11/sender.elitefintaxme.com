<?php

namespace App\Repositories\Admin;

use App\Models\PaymentAccount;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class PaymentAccountRepository
 * @package App\Repositories\Admin
 * @version March 3, 2026, 10:51 pm UTC
 *
 * @method PaymentAccount findWithoutFail($id, $columns = ['*'])
 * @method PaymentAccount find($id, $columns = ['*'])
 * @method PaymentAccount first($columns = ['*'])
*/
class PaymentAccountRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'name',
        'gateway',
        'credentials',
        'is_active',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PaymentAccount::class;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function saveRecord($request)
    {
        $input = $request->all();
        $paymentAccount = $this->create($input);
        return $paymentAccount;
    }

    /**
     * @param $request
     * @param $paymentAccount
     * @return mixed
     */
    public function updateRecord($request, $paymentAccount)
    {
        $input = $request->all();
        $paymentAccount = $this->update($input, $paymentAccount->id);
        return $paymentAccount;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteRecord($id)
    {
        $paymentAccount = $this->delete($id);
        return $paymentAccount;
    }
}
