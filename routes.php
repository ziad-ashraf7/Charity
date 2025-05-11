<?php


use App\controllers\UserController;

$router->get('/', [\App\controllers\HomeController::class, 'index']);


$router->get('user/login', [UserController::class, 'login']);

$router->get('user/register', [UserController::class, 'register']);

