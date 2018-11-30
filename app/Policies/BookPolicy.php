<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Policies\Traits\LinkAccess;

class BookPolicy
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

    public function delete(User $user)
    {
        foreach ($user->roles as $role) {
            if ($role->role == 'admin') {
                return true;
            }
        }
        return false;
    }

    public function create(User $user)
    {
        foreach ($user->roles as $role) {
            if ($role->role == 'admin') {
                return true;
            }
        }
        return false;
    }

    public function update(User $user)
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
