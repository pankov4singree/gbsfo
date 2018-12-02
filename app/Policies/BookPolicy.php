<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Policies\Traits\LinkAccess;

class BookPolicy
{
    use HandlesAuthorization, LinkAccess;

    /**
     * @param User $user
     * @return bool
     */
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
     * @param User $user
     * @return bool
     */
    public function delete(User $user)
    {
        foreach ($user->roles as $role) {
            if ($role->role == 'admin') {
                return true;
            }
        }
        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        foreach ($user->roles as $role) {
            if ($role->role == 'admin') {
                return true;
            }
        }
        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function update(User $user)
    {
        foreach ($user->roles as $role) {
            if ($role->role == 'admin') {
                return true;
            }
        }
        return false;
    }
}
