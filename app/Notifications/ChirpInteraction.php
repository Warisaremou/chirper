<?php

namespace App\Notifications;

use App\Enums\NotificationTypeEnum;
use App\Models\Chirp;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ChirpInteraction extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected NotificationTypeEnum $notificationType,
        protected User $triggeringUser,
        protected Chirp|null $chirp = null
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    /**
     * Get the notification's database type.
     */
    public function databaseType(object $notifiable): string
    {
        return $this->notificationType->value ?? NotificationTypeEnum::LikedChirp->value;
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->triggeringUser->id,
            'chirp_id' => $this->chirp?->id,
        ];
    }

    /**
     * Send push notification
     */
    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Chirper')
            ->icon('/icons/laravel-icon.png')
            ->body("{$this->triggeringUser->name} has {$this->notificationType->notificationMessage()}")
            ->action('View', 'view_account')
            ->options(['TTL' => 60])
            ->data([
                'url' => '/',
                'id' => $notification->id
            ]);
    }
}
