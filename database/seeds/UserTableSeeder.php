<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_superadmin = Role::where('name','superadmin')->first();

        $superadmin = new User();
        $superadmin->name = 'cristian dumitriu';
        $superadmin->email = 'dumitriucristian@yahoo.com';
        $superadmin->password = bcrypt('ucenic');
        $superadmin->save();
        $superadmin->roles()->attach($role_superadmin);
    }
}
