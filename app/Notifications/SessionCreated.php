<?php

namespace App\Notifications;

use App\ChatRoom;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SessionCreated extends Notification
{
    use Queueable;

    /**
     * @var ChatRoom $chatroom
     */
    protected $chatroom;

    /**
     * Create a new notification instance.
     *
     * @param ChatRoom $chatroom
     *
     * @return void
     */
    public function __construct(ChatRoom $chatroom)
    {
        $this->chatroom = $chatroom;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', 'https://laravel.com')
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'time' => Carbon::now(),
            'user' => $notifiable,
            'chat_room' => $this->chatroom,
        ];
    }
}
