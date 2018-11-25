<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Policies\Traits\LinkAccess;

class AuthorPolicy
{
    use HandlesAuthorization, LinkAccess;

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
