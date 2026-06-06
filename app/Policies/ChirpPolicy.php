<?php

namespace App\Policies;

use App\Models\Chirp;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChirpPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Chirp $chirp): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Chirp $chirp): bool
    {
        return $chirp->user()->is($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Chirp $chirp): bool
    {
        return $chirp->user()->is($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Chirp $chirp): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Chirp $chirp): bool
    {
        return $chirp->user()->is($user);
    }

    /**
     * User can like only others chirps
     */
    public function like(User $user, Chirp $chirp): bool
    {
        return $chirp->user()->isNot($user);
    }

    /**
     * User can only unlike chirps they have liked
     */
    public function unlike(User $user, Chirp $chirp): bool
    {
        return $user->likes()
            ->where([
                'user_chirp_likes.user_id' => $user->id,
                'user_chirp_likes.chirp_id' => $chirp->id
            ])
            ->exists();
    }
}