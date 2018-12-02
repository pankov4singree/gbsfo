<?php

namespace App\Policies\Traits;

use App\User;

trait LinkAccess
{
    /**
     * @param User $user
     * @return bool
     */
    public function getDeleteLink(User $user){
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
    public function getEditLink(User $user){
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
    public function getViewLink(User $user){
        return true;
    }
}
