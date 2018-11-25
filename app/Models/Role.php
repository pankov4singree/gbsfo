<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    public $timestamps = false;

    /**
     * get users
     */
    public function users(){
        return $this->belongsToMany('App\Models\User');
    }
}
