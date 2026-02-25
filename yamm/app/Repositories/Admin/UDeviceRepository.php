<?php

namespace App\Repositories\Admin;

use App\Models\UserDevice;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class UDeviceRepository
 * @package App\Repositories\Admin
 * @version July 14, 2018, 9:11 am UTC
 *
 * @method UserDevice findWithoutFail($id, $columns = ['*'])
 * @method UserDevice find($id, $columns = ['*'])
 * @method UserDevice first($columns = ['*'])
 */
class UDeviceRepository extends BaseRepository
{
    /**
     * Returns specified model class name.
     *
     * @return string
     */
    public function model()
    {
        return UserDevice::class;
    }

    /**
     * @param $id
     * @param $request
     */
    public function saveRecord($id, $request)
    {
        $userDeviceData                      = $request->only(['device_token', 'device_type', 'push_notification']);
        $userDeviceData['user_id']           = $id;
        $userDeviceData['push_notification'] = $userDeviceData['push_notification'] ?? 1;
        $userDevice                          = $this->updateOrCreate([
            'user_id'     => \Auth::id(),
            'device_type' => 'web'
        ], $userDeviceData);
        return $userDevice;
    }
}
