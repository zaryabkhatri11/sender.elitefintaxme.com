<?php

namespace App\Repositories\Admin;

use App\Models\Merchant;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class MerchantRepository
 * @package App\Repositories\Admin
 * @version March 3, 2026, 10:56 pm UTC
 *
 * @method Merchant findWithoutFail($id, $columns = ['*'])
 * @method Merchant find($id, $columns = ['*'])
 * @method Merchant first($columns = ['*'])
 */
class MerchantRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'payment_account_id',
        'customer_id',
        'name',
        'email',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Merchant::class;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function saveRecord($request)
    {
        $input = $request->all();
        $merchant = $this->create($input);
        return $merchant;
    }

    /**
     * @param $request
     * @param $merchant
     * @return mixed
     */
    public function updateRecord($request, $merchant)
    {
        $input = $request->all();
        $merchant = $this->update($input, $merchant->id);
        return $merchant;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteRecord($id)
    {
        $merchant = $this->delete($id);
        return $merchant;
    }
}
