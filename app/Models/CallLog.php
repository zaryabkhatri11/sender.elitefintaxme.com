<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CallLog
 * @package App\Models
 * @version March 17, 2026, 1:58 am UTC
 *
 * @property integer user_id
 * @property integer customer_id
 * @property string from_num
 * @property string to_num
 * @property string call_sid
 * @property string status
 * @property integer duration
 * @property string record_url
 */
class CallLog extends Model
{
    use SoftDeletes;

    public $table = 'call_logs';
    
    protected $dates = ['deleted_at'];

    public $fillable = [
        'user_id',
        'customer_id',
        'from_num',
        'to_num',
        'call_sid',
        'status',
        'duration',
        'record_url'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'customer_id' => 'integer',
        'from_num' => 'string',
        'to_num' => 'string',
        'call_sid' => 'string',
        'status' => 'string',
        'duration' => 'integer',
        'record_url' => 'string'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id');
    }
}
