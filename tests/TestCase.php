<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Collection;
use \App\Quiz;
use \App\User;

abstract class TestCase extends BaseTestCase
{

    use CreatesApplication;

    use DatabaseMigrations;
    public function setUp(){

        parent::setUp();

        $role = factory(\App\Role::class,1)->create(array('name'=>'superadmin',"description"=>"ssdsfds asdfasfas"));
        $user = factory(User::class)->create();
        $roleUser = factory(\App\RoleUser::class, 1)->create(array('user_id'=>1, 'role_id'=>1));
        $user->roles()->attach(1);
        $this->actingAs($user);



    }
}
