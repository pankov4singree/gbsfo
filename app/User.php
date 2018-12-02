<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * get roles
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }

    /**
     * @param string $route
     * @param string $title
     * @return string
     */
    public function buildAdminLink($route = "admin.home", $title = "Admin")
    {
        if (!empty($this->roles)) {
            foreach ($this->roles as $role) {
                if ($role->role == 'admin') {
                    return '<a class="pull-right" href="' . route($route) . '">' . $title . '</a>';
                }
            }
        }
        return '';
    }
}
