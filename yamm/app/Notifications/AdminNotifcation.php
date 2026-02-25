<?php

namespace App\Notifications;

use Edujugon\PushNotification\Channels\FcmChannel;
use Edujugon\PushNotification\Messages\PushMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Notification as Notify;

class AdminNotifcation extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $ref_id;
    private $message;
    private $broadcast_type;

    public function __construct($ref_id, $message, $broadcast_type)
    {
        $this->ref_id         = $ref_id;
        $this->message        = $message;
        $this->broadcast_type = $broadcast_type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        switch ($this->broadcast_type) {
            case Notify::BROADCAST_TYPE_SAVE_AND_MAIL:
                return ['database', 'mail'];
            case Notify::BROADCAST_TYPE_SAVE_AND_PUSH:
                return ['database', FcmChannel::class];
            case Notify::BROADCAST_TYPE_ALL:
                return ['database', FcmChannel::class, 'mail'];
            default:
                return ['database'];
        }
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
            ->line($notifiable->details->full_name)
            ->line($this->message)
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

    /**
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'sender_id'   => \Auth::id(),
            'url'         => '',
            'action_type' => Notify::ACTION_TYPE_USER,
            'ref_id'      => $this->ref_id,
            'message'     => $this->message,
        ];
    }

    /**
     * @param $notifiable
     * @return PushMessage
     */
    public function toFcm($notifiable)
    {
        return (new PushMessage)
            ->title(config('app.name'))
            ->config(['dry_run' => false])
            ->body($this->message)
            ->sound('default')
            ->icon(\url("/public/images/fav.png"))
            ->clickAction(url('/'))
            ->extra([
                'ref_id'      => $this->ref_id,
                'action_type' => Notify::ACTION_TYPE_USER
            ]);
    }
}
