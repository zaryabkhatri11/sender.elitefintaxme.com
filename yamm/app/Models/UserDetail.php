<?php

namespace App\Models;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property integer id
 * @property integer user_id
 * @property string first_name
 * @property string last_name
 * @property string phone
 * @property string address
 * @property string image
 * @property integer area_id
 * @property integer is_verified
 * @property integer email_updates
 * @property integer is_social_login
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 *
 * @property string image_url
 *
 * @property User user
 *
 * )
 */
class UserDetail extends Model
{
    use SoftDeletes;
    public $table = 'user_details';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    const MALE   = 10;
    const FEMALE = 20;

    public static $GENDER = [
        self::MALE   => 'Male',
        self::FEMALE => 'Female',
    ];
    const PERSONAL = 1;
    const PARENTAL = 0;

    public static $USAGE = [
        self::PERSONAL => 'Personal',
        self::PARENTAL => 'Parent',
    ];

    const THERAPY     = 1;
    const NON_THERAPY = 0;

    public static $CONDITION = [
        self::THERAPY     => 'yes',
        self::NON_THERAPY => 'no',
    ];


    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'phone', 'address', 'image', 'email_updates', 'is_social_login', 'dob', 'gender', 'is_personal', 'is_therapy', 'goals', 'concerns', 'functional', 'total_points'
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
    protected $appends = [
        'image_url',
    ];

    /**
     * The attributes that should be visible in toArray.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'first_name',
        'last_name',
        'phone',
        'address',
        'image',
        'image_url',
        'area_id',
        'is_verified',
        'email_updates',
        'is_social_login', 'dob', 'gender', 'is_personal', 'is_therapy', 'goals', 'concerns', 'functional', 'total_points'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return ($this->image && storage_path(url('storage/app/' . $this->image))) ? config('filesystems.disks.s3.url') . $this->image : route('api.resize', ['img' => 'users/user.png', 'w=100', 'h=100']);
    }
}
