<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Authors::class);
        $this->call(Categories::class);
        $this->call(Books::class);
        $this->call(Roles::class);
    }
}
