<?php

namespace App\Controllers;

use App\Models\Users as Users;

class UsersController extends Controller
{
    public $usersManager;

    function __construct()
    {
        $this->usersManager = new Users();
    }

    function index()
    {
        print_r($this->usersManager->all());
    }
}