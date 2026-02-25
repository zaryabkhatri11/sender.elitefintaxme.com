<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string id
 * @property string type
 * @property string notifiable_type
 * @property integer notifiable_id
 * @property string data
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 *
 * @property User users
 *
 * @SWG\Definition(
 *      definition="Notification",
 *      required={"type", "notifiable_type", "notifiable_id", "data"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="notifiable_type",
 *          description="notifiable_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="notifiable_id",
 *          description="notifiable_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="data",
 *          description="data",
 *          type="string"
 *      )
 * )
 */
class Notification extends Model
{
    use SoftDeletes;

    public $table = 'notifications';

    protected $dates = ['deleted_at'];

    const ACTION_TYPE_USER = 10;

    const RECEIVER_TYPE_IOS     = 10;
    const RECEIVER_TYPE_ANDRIOD = 20;
    const RECEIVER_TYPE_WEB     = 30;
    const RECEIVER_TYPE_ALL     = 40;

    const BROADCAST_TYPE_SAVE_ONLY     = 10;
    const BROADCAST_TYPE_MAIL          = 20;
    const BROADCAST_TYPE_PUSH          = 30;
    const BROADCAST_TYPE_SAVE_AND_MAIL = 40;
    const BROADCAST_TYPE_SAVE_AND_PUSH = 50;
    const BROADCAST_TYPE_ALL           = 60;

    public static $ACTION_TYPES    = [
        self::ACTION_TYPE_USER => 'users'
    ];
    public static $RECEIVER_TYPES  = [
        self::RECEIVER_TYPE_IOS     => 'IOS',
        self::RECEIVER_TYPE_ANDRIOD => 'Android',
        self::RECEIVER_TYPE_WEB     => 'Web',
        self::RECEIVER_TYPE_ALL     => 'All',
    ];
    public static $BROADCAST_TYPES = [
        self::BROADCAST_TYPE_SAVE_ONLY     => 'Save Only',
        self::BROADCAST_TYPE_SAVE_AND_MAIL => 'Save and Mail',
        self::BROADCAST_TYPE_SAVE_AND_PUSH => 'Save and Sent Push',
        self::BROADCAST_TYPE_ALL           => 'All',
    ];

    public $fillable = [
        'type',
        'notifiable_type',
        'notifiable_id',
        'data'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'              => 'string',
        'type'            => 'string',
        'notifiable_type' => 'string',
        'notifiable_id'   => 'integer',
        'data'            => 'array'
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
        'ref_id'         => 'required',
        'message'        => 'required',
        'broadcast_type' => 'required',
        'device_type'    => 'required',
    ];

    /**
     * Validation update rules
     *
     * @var array
     */
    public static $update_rules = [
        'ref_id'         => 'required',
        'message'        => 'required',
        'broadcast_type' => 'required',
        'device_type'    => 'required',
    ];

    /**
     * Validation api rules
     *
     * @var array
     */
    public static $api_rules = [
        'message' => 'required'
    ];
}
