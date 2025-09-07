<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $loggedInUser, User $user): bool
    {
        return $loggedInUser->id === $user->id;
    }
}
