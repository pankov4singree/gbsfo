<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Policies\Traits\LinkAccess;

class CategoryPolicy
{
    use HandlesAuthorization, LinkAccess;

    public function edit(User $user)
    {
        foreach ($user->roles as $role) {
            if ($role->role == 'admin') {
                return true;
            }
        }
        return false;
    }

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
