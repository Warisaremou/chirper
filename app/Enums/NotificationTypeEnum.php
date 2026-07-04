<?php

namespace App\Enums;

enum NotificationTypeEnum: string
{
    case LikedChirp = 'liked_chirp';
    case NewFollow = 'new_follow';

    public function notificationMessage(): string
    {
        return match ($this) {
            self::LikedChirp => 'liked your chirp',
            self::NewFollow => 'started following you',
        };
    }
}