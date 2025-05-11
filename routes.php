<?php


use App\controllers\HomeController;
use App\controllers\UserController;


$router->get('/', [HomeController::class, 'index']);

$router->get('/user/login', [UserController::class, 'loginView']);

$router->get('/user/register', [UserController::class, 'registerView']);

$router->post('/user/signup', [UserController::class, 'signUp']);

$router->post('/user/login', [UserController::class, 'logIn']);
