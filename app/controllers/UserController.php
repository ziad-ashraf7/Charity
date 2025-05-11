<?php

namespace App\controllers;

use App\models\UserModel;
use Core\Flash;
use Core\Session;
use Core\Validation;


class UserController
{
    public function __construct(
        private userModel $userModel
    )
    {
    }

    public function index($params)
    {
        $user_email = Session::get('user')['email'];
        $user = $this->userModel->getUserByEmail($user_email);
        loadView(
            'user/index',
            [
                'user' => $user
            ]
        );
    }

    public function login()
    {
        loadView('user/login');
    }

    public function register()
    {
        loadView('user/register');
    }

    public function store($params)
    {
        $requiredFields = [
            'name',
            'phone',
            'email',
            'password'
        ];

        $data = fetchFields($requiredFields);
        $errors = [];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || !strlen($data[$field]) || !Validation::string($data[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        if (!Validation::string($data['phone'], 0, 11)) {
            $errors['phone'] = 'Phone number is invalid';
        }

        if (!Validation::email($data['email'])) {
            $errors['email'] = 'Email is invalid';
        }

        if ($message = Validation::exists('users', 'email', $data['email'], 'Email already exists')) {
            $errors['email'] = $message;
        }

        if (!Validation::string($data['password'], 6, 255)) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if (empty($errors) && isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        viewErrorsIfExist($errors);

        $this->userModel->createUser($data);
        $userID = $this->userModel->getLastUserId();
        $this->cartModel->mergeCart($userID);
        Session::set(
            "user",
            [
                "id" => $this->userModel->getLastUserId(),
                "name" => $data['name'],
                "email" => $data['email'],
                "role" => "customer"
            ]
        );
        Flash::set(Flash::SUCCESS, "You are now logged in");
        redirect("/");
    }

    public function authenticate()
    {
        $requiredFields = [
            'email',
            'password'
        ];

        $data = [];
        $errors = [];

        foreach ($_POST as $key => $value) {
            if (in_array($key, $requiredFields)) {
                $data[$key] = sanitize($value);
            }
        }

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || !strlen($data[$field]) || !Validation::string($data[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        if (!Validation::email($data['email'])) {
            $errors['email'] = 'Email is invalid';
        }

        $user = $this->userModel->getUserByEmail($data['email']);

        if ($user && password_verify($data['password'], $user->password)) {
            Session::set("user", [
                "id" => $user->user_id,
                "name" => $user->name,
                "email" => $user->email,
                "role" => $user->role
            ]);
            if (!$this->cartModel->getCartId($user->user_id)) {
                $this->cartModel->createCart($user->user_id);
            }
            $this->cartModel->mergeCart($user->user_id);
            Flash::set(Flash::SUCCESS, "You are now logged in");
            redirect("/");
        } else {
            if (!empty($errors)) {
                foreach ($errors as $error => $message) {
                    Flash::set(Flash::ERROR, $message);
                }
            } else {
                Flash::set(Flash::ERROR, "Invalid email or password");
            }
            redirect("/login");
        }
    }

    public function logout()
    {
        Session::destroy();
        redirect("/");
    }
}
