<?php

namespace App\controllers;

use Core\Session;
use Core\System;

class BaseController
{
    protected $authUser;
    public function __construct()
    {
        $this->authUser = Session::get(System::USER);
    }
}