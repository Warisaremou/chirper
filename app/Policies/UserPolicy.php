<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the authenticated user can follow the target user.
     */
    public function follow(User $user, User $target): bool
    {
        $isAllowed = $user->isNot($target) && !$user->followings()->where('following_id', $target->id)->exists();
        return $isAllowed;
    }

    /**
     * Determine if the authenticated user can unfollow the target user.
     */
    public function unfollow(User $user, User $target): bool
    {
        $isAllowed = $user->isNot($target) && $user->followings()->where('following_id', $target->id)->exists();
        return $isAllowed;
    }
}