<?php

use Illuminate\Database\Seeder;
use \App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_quest = new Role();
        $role_quest->name = 'guest';
        $role_quest->description = 'User that can complete and see only his quizzes authenticated as anonymous';
        $role_quest->save();

        $role_subscriber = new Role();
        $role_subscriber->name = 'subscriber';
        $role_subscriber->description = 'User that is authenticated can complete and see his quizzes, history, usage statistics and quiz statistics';
        $role_subscriber->save();

        $role_superadmin = new Role();
        $role_superadmin->name = 'superadmin';
        $role_superadmin->description = "The mighty king of kings";
        $role_superadmin->save();
    }
}
