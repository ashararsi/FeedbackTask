<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

use Kutia\Larafirebase\Messages\FirebaseMessage;


class RealTimeNotification extends Notification implements ShouldBroadcast

{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public array $message;

    public function __construct(array $message)
    {
//        dd($message);
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
            return ['firebase','broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }
    public function toBroadcast($notifiable): BroadcastMessage
    {
          $content=(string) $this->message['body'];
//        dd($this->message['body']);
        return new BroadcastMessage([
            'message' => "$content (User $notifiable->id)",
            'event' => class_basename($this),
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toFirebase($notifiable)
    {
        $deviceTokens = User::whereNotNull('device_token')->pluck('device_token')->all();
    return (new FirebaseMessage)
            ->withTitle($this->message['title'], $notifiable->first_name)
            ->withBody($this->message['body'])
            ->asNotification($deviceTokens); // OR ->asMessage($deviceTokens);
    }
}
