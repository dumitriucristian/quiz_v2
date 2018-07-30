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

        $user = factory(User::class)->create();
        $this->actingAs($user);



    }
}
