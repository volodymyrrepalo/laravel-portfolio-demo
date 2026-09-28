<?php

namespace App\Policies;

use App\Models\MapPin;
use App\Models\User;

class MapPinPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MapPin $mapPin): bool
    {
        return $mapPin->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MapPin $mapPin): bool
    {
        return $mapPin->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MapPin $mapPin): bool
    {
        return $mapPin->user_id === $user->id;
    }
}
