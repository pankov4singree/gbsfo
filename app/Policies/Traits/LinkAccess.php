<?php

namespace App\Policies\Traits;

use App\User;

trait LinkAccess
{
    public function getDeleteLink(User $user){
        foreach ($user->roles as $role) {
            if ($role->role == 'admin') {
                return true;
            }
        }
        return false;
    }

    public function getEditLink(User $user){
        foreach ($user->roles as $role) {
            if ($role->role == 'admin') {
                return true;
            }
        }
        return false;
    }

    public function getViewLink(User $user){
        return true;
    }
}
