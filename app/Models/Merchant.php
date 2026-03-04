<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property integer $payment_account_id
 * @property string $name
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @SWG\Definition(
 *      definition="Merchant",
 *      required={"payment_account_id", "name", "email"},
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Merchant extends Model
{
    use SoftDeletes;

    public $table = 'merchants';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'payment_account_id',
        'name',
        'email',
        'payment_link'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'email' => 'string'
    ];

    /**
     * The objects that should be append to toArray.
     *
     * @var array
     */
    protected $with = [];

    /**
     * The attributes that should be append to toArray.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * The attributes that should be visible in toArray.
     *
     * @var array
     */
    protected $visible = [];

    /**
     * Validation create rules
     *
     * @var array
     */
    public static $rules = [
        'payment_account_id' => 'required',
        'name' => 'required',
        'email' => 'required|email',
        'amount' => 'required|numeric|min:0.01'
    ];

    /**
     * Validation update rules
     *
     * @var array
     */
    public static $update_rules = [
        'payment_account_id' => 'required',
        'name' => 'required',
        'email' => 'required|email'
    ];

    /**
     * Validation api rules
     *
     * @var array
     */
    public static $api_rules = [
        'payment_account_id' => 'required',
        'name' => 'required',
        'email' => 'required|email'
    ];

    /**
     * Validation api update rules
     *
     * @var array
     */
    public static $api_update_rules = [
        'payment_account_id' => 'required',
        'name' => 'required',
        'email' => 'required|email'
    ];

    /**
     * Get the payment account that owns the merchant.
     */
    public function paymentAccount()
    {
        return $this->belongsTo(PaymentAccount::class, 'payment_account_id');
    }
}
