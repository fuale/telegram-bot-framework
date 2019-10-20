<?php

namespace App\Scenario\Controllers;

use App\Scenario\Repositories\UserRepository;

class ExampleController
{

    /**
     * @var \App\Scenario\Repositories\UserRepository
     */
    private $user;

    public function __construct(UserRepository $user)
    {
        $users = $user->all()->get();
    }
}
