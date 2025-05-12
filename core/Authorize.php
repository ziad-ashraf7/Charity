<?php

namespace Core;

use Core\Session;

class Authorize
{

    public function handle($middlewares)
    {
        $userRole = Session::get(System::USER)['role'] ?? null;
        $isAuthorized = false;

        foreach ($middlewares as $middleware) {
            if ($middleware === 'guest') {
                $isAuthorized = ($userRole === null);
            } else {
                $isAuthorized = ($userRole === $middleware);
            }
            if ($isAuthorized) {
                break;
            }
        }
        if (!$isAuthorized) {
            $path = match ($userRole) {
                System::USER => '/user/login',
                System::ADMIN => '/admin/login',
                default => '/user/login',
            };
            redirect($path);
        }
    }
}