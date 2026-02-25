<?php

namespace App\Repositories\Admin;

use App\Models\Email;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class EmailRepository
 * @package App\Repositories\Admin
 * @version September 12, 2025, 8:11 pm UTC
 *
 * @method Email findWithoutFail($id, $columns = ['*'])
 * @method Email find($id, $columns = ['*'])
 * @method Email first($columns = ['*'])
*/
class EmailRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'emails',
        'status',
        'created_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Email::class;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function saveRecord($request)
    {
        $input = $request->all();
        $email = $this->create($input);
        return $email;
    }

    /**
     * @param $request
     * @param $email
     * @return mixed
     */
    public function updateRecord($request, $email)
    {
        $input = $request->all();
        $email = $this->update($input, $email->id);
        return $email;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteRecord($id)
    {
        $email = $this->delete($id);
        return $email;
    }
}
