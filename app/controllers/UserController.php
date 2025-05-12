<?php

namespace App\controllers;

use App\models\UserModel;
use App\models\UserPaymentCardModel;
use Core\FileUploader;
use Core\Flash;
use Core\Session;
use Core\Validation;


class UserController
{
    private userModel $userModel;
    public const IMG_PATH = 'user/pics';

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
        if (!$user) {
            $errors['email'] = 'Wrong Email';
            viewErrorsIfExist($errors, '/user/login');
        } else if (password_verify($data['password'], $user->password)) {
            Session::set("user", [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "role" => 'user'
            ]);
            Flash::set(Flash::SUCCESS, "You are now logged in");
            redirect("/");
        } else {
            $errors['password'] = 'Wrong password';
            viewErrorsIfExist($errors, '/user/login');
        }
    }

    public function logout()
    {
        Session::destroy();
        Flash::set(Flash::ERROR, "You are now logged out");
        redirect('/');
    }

    public function profileView()
    {
        $userId = Session::get('user')['id'];
        $user = $this->userModel->getUserById($userId);
        $paymentMethods = (new UserPaymentCardModel())->getPaymentMethods($userId);
        loadView('/user/profile', [
            'user' => $user,
            'paymentMethods' => $paymentMethods
        ]);
    }

    public function updatePersonalInfo()
    {
        $requiredFields = [
            'first_name',
            'last_name',
            'email',
            'phone',
            'address',
            'city',
            'country'
        ];

        $data = fetchFields($requiredFields);
        $errors = checkRequiredFields($requiredFields, $data);

        //inspectAndDie($errors, $_POST);

        $oldModel = $this->userModel->getUserById(Session::get('user')['id']);


        if (!Validation::email($data['email'])) {
            $errors['email'] = 'Email is invalid';
        }
        if ($oldModel->email != $data['email'] && Validation::exists('users', 'email', $data['email'], 'Email already exists') !== false) {
            $errors['email'] = 'Email already exists';
        }
        if ($oldModel->phone != $data['phone'] && Validation::exists('users', 'phone', $data['phone'], 'Phone already exists') !== false) {
            $errors['email'] = 'Phone already exists';
        }

        viewErrorsIfExist($errors, '/user/profile');

        //inspectAndDie($data);
        $data['id'] = Session::get('user')['id'];
        //inspectAndDie($data);
        $this->userModel->updatePersonalInfo($data);
        Flash::set(Flash::SUCCESS, "Personal info is updated");
        redirect('/user/profile');
    }

    public function updateImg()
    {
        $userModel = $this->userModel->getUserById(Session::get('user')['id']);
        $imgRes = FileUploader::upload($_FILES['img'], self::IMG_PATH, inputName: 'Profile Pic', oldFile: $userModel->img);
        $this->userModel->updateImage([
            'img' => $imgRes[1],
            'id' => $userModel->id,
        ]);
        Flash::set(Flash::SUCCESS, "Image is updated");
        redirect('/user/profile');
    }

    public function updatePassword()
    {
        $requiredFields = [
            'current_password',
            'new_password',
            'confirm_password'
        ];
        $data = fetchFields($requiredFields);
        $errors = checkRequiredFields($requiredFields, $data);

        $userId = Session::get('user')['id'];

        if (!Validation::checkOldPassword('users', $userId, $data['current_password']))
            $errors['current_password'] = 'Old password is wrong';

        if ($data['new_password'] != $data['confirm_password'])
            $errors['confirm_password'] = 'Confirm password do not match';

        if (!Validation::string($data['new_password'], 6, 255)) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        viewErrorsIfExist($errors, '/user/profile');

        $this->userModel->updatePassword([
            'id' => $userId,
            'password' => password_hash($data['new_password'], PASSWORD_DEFAULT)
        ]);
        Flash::set(Flash::SUCCESS, "Password is updated");
        redirect('/user/profile');
    }
}
