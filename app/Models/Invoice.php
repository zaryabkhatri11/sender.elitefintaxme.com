<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @SWG\Definition(
 *      definition="Invoice",
 *      required={"id", "merchant_id", "uuid", "amount", "currency", "description", "status", "created_at", "updated_at"},
 *      @SWG\Property(
 *          property="uuid",
 *          description="uuid",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="amount",
 *          description="amount",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="currency",
 *          description="currency",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
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
class Invoice extends Model
{
    use SoftDeletes;

    public $table = 'invoices';


    protected $dates = ['deleted_at'];


    public $fillable = [
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'uuid' => 'string',
        'amount' => 'decimal:2',
        'currency' => 'string',
        'description' => 'string',
        'status' => 'string'
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
        'id' => 'required',
        'merchant_id' => 'required',
        'uuid' => 'required',
        'amount' => 'required',
        'currency' => 'required',
        'description' => 'required',
        'status' => 'required',
        'created_at' => 'required',
        'updated_at' => 'required'
    ];

    /**
     * Validation update rules
     *
     * @var array
     */
    public static $update_rules = [
        'id' => 'required',
        'merchant_id' => 'required',
        'uuid' => 'required',
        'amount' => 'required',
        'currency' => 'required',
        'description' => 'required',
        'status' => 'required',
        'created_at' => 'required',
        'updated_at' => 'required'
    ];

    /**
     * Validation api rules
     *
     * @var array
     */
    public static $api_rules = [
        'id' => 'required',
        'merchant_id' => 'required',
        'uuid' => 'required',
        'amount' => 'required',
        'currency' => 'required',
        'description' => 'required',
        'status' => 'required',
        'created_at' => 'required',
        'updated_at' => 'required'
    ];

    /**
     * Validation api update rules
     *
     * @var array
     */
    public static $api_update_rules = [
        'id' => 'required',
        'merchant_id' => 'required',
        'uuid' => 'required',
        'amount' => 'required',
        'currency' => 'required',
        'description' => 'required',
        'status' => 'required',
        'created_at' => 'required',
        'updated_at' => 'required'
    ];


    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }
}
