<?php

namespace App\Repositories\Admin;

use App\Models\MessagesLog;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class MessagesLogRepository
 * @package App\Repositories\Admin
 * @version March 16, 2026, 6:24 pm UTC
 *
 * @method MessagesLog findWithoutFail($id, $columns = ['*'])
 * @method MessagesLog find($id, $columns = ['*'])
 * @method MessagesLog first($columns = ['*'])
*/
class MessagesLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'user_id',
        'customer_id',
        'from_num',
        'to_num',
        'body',
        'direction',
        'message_sid',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MessagesLog::class;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function saveRecord($request)
    {
        $input = $request->all();
        $messagesLog = $this->create($input);
        return $messagesLog;
    }

    /**
     * @param $request
     * @param $messagesLog
     * @return mixed
     */
    public function updateRecord($request, $messagesLog)
    {
        $input = $request->all();
        $messagesLog = $this->update($input, $messagesLog->id);
        return $messagesLog;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteRecord($id)
    {
        $messagesLog = $this->delete($id);
        return $messagesLog;
    }
}
