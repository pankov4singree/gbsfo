<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\User;

class Roles extends Seeder
{

    protected $roles = [
        'user',
        'moderator',
        'admin'
    ];

    protected $role_ids = [];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->roles as $role) {
            $role = new Role(['role' => $role]);
            $role->save();
            $this->role_ids[] = $role->id;
        }
        $user = new User();
        $user->name = 'admin';
        $user->email = 'admin@email.com';
        $user->password = bcrypt('test');
        $user->save();
        $user->roles()->sync($this->role_ids);
    }
}
