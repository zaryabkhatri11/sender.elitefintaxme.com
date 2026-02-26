<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property string $email
 * @property string $phone
 * @property string $owner_name
 * @property string $entity
 * @property string $owner_address
 * @property string $subject_mark
 * @property string $case_number
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @SWG\Definition(
 *      definition="Customer",
 *      required={"id", "email", "phone", "owner_name", "entity", "owner_address", "subject_mark", "case_number", "created_at", "updated_at", "deleted_at"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="phone",
 *          description="phone",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="owner_name",
 *          description="owner_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="entity",
 *          description="entity",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="owner_address",
 *          description="owner_address",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="subject_mark",
 *          description="subject_mark",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="case_number",
 *          description="case_number",
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
 *      ),
 *      @SWG\Property(
 *          property="deleted_at",
 *          description="deleted_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Customer extends Model
{
    use SoftDeletes;

    public $table = 'customers';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'email',
        'phone',
        'owner_name',
        'entity',
        'owner_address',
        'subject_mark',
        'case_number',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'email' => 'string',
        'phone' => 'string',
        'owner_name' => 'string',
        'entity' => 'string',
        'owner_address' => 'string',
        'subject_mark' => 'string',
        'case_number' => 'string',
        'status' => 'string'
    ];

    /**
     * Validation rules
     */
    public static $rules = [
        'email' => 'required',
        'phone' => 'required',
        'owner_name' => 'required',
        'entity' => 'required',
        'owner_address' => 'required',
        'subject_mark' => 'required',
        'case_number' => 'required',
    ];
}
