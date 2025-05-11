<?php

namespace App\controllers;

use App\models\UserModel;
use Core\Flash;
use Core\Session;
use Core\Validation;


class UserController
{
    private userModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
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

    public function loginView()
    {
        loadView('user/login');
    }

    public function registerView()
    {
        loadView('user/register');
    }

    public function signUp($params)
    {
        $requiredFields = [
            'first_name',
            'last_name',
            'phone',
            'email',
            'password',
            'confirm_password',
        ];

        $data = fetchFields($requiredFields);

        $errors = checkRequiredFields($requiredFields, $data);
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
        viewErrorsIfExist($errors, '/user/register');

        $this->userModel->signUp($data);
        $userID = $this->userModel->getLastUserId();
        Session::set(
            "user", [
                "id" => $userID,
                "name" => $data['name'],
                "email" => $data['email'],
                "role" => "user"
            ]
        );
        Flash::set(Flash::SUCCESS, "You are now logged in");
        redirect("/");
    }

    public function login()
    {
        $requiredFields = [
            'email',
            'password'
        ];

        $data = fetchFields($requiredFields);
        $errors = checkRequiredFields($requiredFields, $data);

        //inspectAndDie($errors,$data);

        if (!Validation::email($data['email'])) {
            $errors['email'] = 'Email is invalid';
        }

        viewErrorsIfExist($errors, '/user/login');

        $user = $this->userModel->getUserByEmail($data['email']);
        if ($user && password_verify($data['password'], $user->password)) {
            Session::set("user", [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "role" => 'user'
            ]);
            Flash::set(Flash::SUCCESS, "You are now logged in");
            redirect("/");
        } else{
            $errors['password'] = 'Wrong password';
            viewErrorsIfExist($errors, '/user/login');
        }
    }

    public function logout()
    {
        Session::destroy();
        redirect("/");
    }
}
