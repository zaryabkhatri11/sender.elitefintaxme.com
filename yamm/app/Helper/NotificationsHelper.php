<?php

namespace App\Helper;

use Edujugon\PushNotification\PushNotification;
use Illuminate\Support\Facades\Config;

class NotificationsHelper
{
    function sendPushNotifications($msg = '', $deviceObject = [], $extraPayLoadData = [])
    {
        $androidDeviceToken = [];
        $iosDeviceToken     = [];
        $webDeviceToken     = [];

        foreach ($deviceObject as $device):
            if (strtolower($device['device_type']) == 'android') {
                $androidDeviceToken[] = $device['device_token'];
            } elseif (strtolower($device['device_type']) == 'ios') {
                $iosDeviceToken[] = $device['device_token'];
            } elseif (strtolower($device['device_type']) == 'web') {
                $webDeviceToken[] = $device['device_token'];
            }
        endforeach;

        if ($androidDeviceToken) {
            $push = new PushNotification('fcm');
            $push->setMessage([
                'notification' => [
                    'title' => config('app.name'),
                    'body'  => $msg,
                    'sound' => 'default'
                ],
                'data'         => [
                    'extra_payload' => $extraPayLoadData
                ],
                'android'      => [
                    'ttl'          => '86400',
                    'notification' => [
                        'click_action' => 'MainActivity'
                    ]
                ]
            ])
                ->setApiKey(Config::get('pushnotification.fcm.apiKey'))
                ->setConfig(['dry_run' => false])
                ->setDevicesToken($androidDeviceToken)
                ->send();
        }
        if ($webDeviceToken) {
            $push = new PushNotification('fcm');
            $push->setMessage([
                'notification' => [
                    'title'        => config('app.name'),
                    'body'         => $msg,
                    'sound'        => 'default',
                    'icon'         => \url("/public/images/fav.png"),
                    'click_action' => isset($extraPayLoadData['click_action']) ? $extraPayLoadData['click_action'] : url('/')
                ],
                'data'         => [
                    'ref_id' => $extraPayLoadData['ref_id'],
                ]
            ])
                ->setApiKey(Config::get('pushnotification.fcm.apiKey'))
                ->setConfig(['dry_run' => false])
                ->setDevicesToken($webDeviceToken)
                ->send();
        }

        /*if ($androidDeviceToken) {
            $push = new PushNotification('fcm');
            $push->setMessage([
                'notification' => [
                    'title' => config('app.name'),
                    'body'  => $msg,
                    'sound' => 'default'
                ],
                'data'         => [
                    'action_type' => $extraPayLoadData['action_type'],
                    'ref_id'      => $extraPayLoadData['ref_id'],
                    'sender_id'   => $extraPayLoadData['sender_id']
                ],
                'android'      => [
                    'ttl'          => '86400',
                    'notification' => [
                        'click_action' => 'MainActivity'
                    ]
                ]
            ])
                ->setApiKey(Config::get('constants.pushNotification.fcm'))
                ->setConfig(['dry_run' => false])
                ->setDevicesToken($androidDeviceToken)
                ->send();
        }*/

        /*Apn*/
        if ($iosDeviceToken) {
            $push = new PushNotification('fcm');
            $push->setMessage([
//                'notification' => [
//                    'title' => config('app.name'),
//                    'body'  => $msg,
//                    'sound' => 'default'
//                ],
                'data'         => [
                    'extra_payload' => $extraPayLoadData
                ],
                'android'      => [
                    'ttl'          => '86400',
                    'notification' => [
                        'click_action' => 'MainActivity'
                    ]
                ]
            ])
                ->setApiKey(Config::get('pushnotification.fcm.apiKey'))
                ->setConfig(['dry_run' => false])
                ->setDevicesToken($androidDeviceToken)
                ->send();
        }
        return true;
    }
}


