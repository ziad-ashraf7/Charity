<?php

namespace App\controllers;
class HomeController {
    public function index()
    {
        loadView('home');
    }
}