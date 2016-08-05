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
        // $this->call(UsersTableSeeder::class);
        $roles = [
            ['name' => 'orchestra'],
            ['name' => 'musician'],
            ['name' => 'member']
        ];
        foreach($roles as $role){
            \App\Role::create($role);
        }
    }
}
