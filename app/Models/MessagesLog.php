<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer id
 * @property string name
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 *
 * @SWG\Definition(
 *      definition="MessagesLog",
 *      required={"id", "user_id", "customer_id", "from_num", "to_num", "body", "direction", "message_sid", "status", "created_at", "updated_at"},
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="customer_id",
 *          description="customer_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="from_num",
 *          description="from_num",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="to_num",
 *          description="to_num",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="body",
 *          description="body",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="direction",
 *          description="direction",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="message_sid",
 *          description="message_sid",
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
class MessagesLog extends Model
{
    use SoftDeletes;

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public $table = 'sms_logs';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'user_id',
        'customer_id',
        'from_num',
        'to_num',
        'body',
        'direction',
        'message_sid',
        'status',
        'is_read',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'customer_id' => 'integer',
        'from_num' => 'string',
        'to_num' => 'string',
        'body' => 'string',
        'direction' => 'string',
        'message_sid' => 'string',
        'status' => 'string',
        'is_read' => 'boolean'
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
        'user_id' => 'nullable',
        'customer_id' => 'nullable',
        'from_num' => 'nullable',
        'to_num' => 'required',
        'body' => 'required',
        'direction' => 'required',
        'message_sid' => 'nullable',
        'status' => 'nullable'
    ];

    /**
     * Validation update rules
     *
     * @var array
     */
    public static $update_rules = [
        'user_id' => 'nullable',
        'customer_id' => 'nullable',
        'from_num' => 'nullable',
        'to_num' => 'required',
        'body' => 'required',
        'direction' => 'required',
        'message_sid' => 'nullable',
        'status' => 'nullable'
    ];

    /**
     * Validation api rules
     *
     * @var array
     */
    public static $api_rules = [
        'user_id' => 'nullable',
        'customer_id' => 'nullable',
        'from_num' => 'nullable',
        'to_num' => 'required',
        'body' => 'required',
        'direction' => 'required',
        'message_sid' => 'nullable',
        'status' => 'nullable'
    ];
	
	/**
     * Validation api update rules
     *
     * @var array
     */
    public static $api_update_rules = [
        'user_id' => 'nullable',
        'customer_id' => 'nullable',
        'from_num' => 'nullable',
        'to_num' => 'required',
        'body' => 'required',
        'direction' => 'required',
        'message_sid' => 'nullable',
        'status' => 'nullable'
    ];

    
}
