<?php

namespace App\Notifications;

use Edujugon\PushNotification\Channels\FcmChannel;
use Edujugon\PushNotification\Messages\PushMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;

class TestPushNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [FcmChannel::class, 'database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'sender_id'   => \Auth::id(),
            'url'         => '',
            'action_type' => \App\Models\Notification::ACTION_TYPE_USER,
            'ref_id'      => 1,
            'message'     => 'Test Push Notification',
        ];
    }

    public function toFcm($notifiable)
    {
        return (new PushMessage)
            ->title(config('app.name'))
            ->config(['dry_run' => false])
            ->body("Test Push Notification")
            ->sound('default')
            ->icon(\url("/public/images/fav.png"))
            ->clickAction(url('/'))
            ->extra([]);
    }
}
