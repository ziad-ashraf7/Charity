<?php

namespace App\controllers;

use App\models\AdminModel;
use Core\Session;
use Core\Flash;
use Core\System;
use Core\Validation;

class AdminController extends BaseController
{
    private AdminModel $adminModel;

    public function __construct()
    {
        parent::__construct();
        $this->adminModel = new AdminModel();
    }

    public function createDummy()
    {
        $email = 'ahmed@gmail.com';
        $isEmailExists = Validation::exists('admins', 'email', $email, 'already found');
        if ($isEmailExists !== false) die("Already Exists");
        $this->adminModel->addNewAdmin([
            'name' => 'Ahmed Arafat',
            'email' => $email,
            'password' => password_hash('123', PASSWORD_DEFAULT),
        ]);
        die("Done Creating The Account");
    }

    public function loginView()
    {
        loadView('admin/login');
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
        viewErrorsIfExist($errors, '/admin/login');


        $admin = $this->adminModel->getAdminByEmail($data['email']);

        if ($admin && password_verify($data['password'], $admin->password)) {
            Session::set(System::USER, [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => System::ADMIN
            ]);
            Flash::set(Flash::SUCCESS, "You are now logged in as an Admin");
            redirect('/');
        } else {
            $errors['password'] = 'Wrong password';
            viewErrorsIfExist($errors, '/admin/login');
        }
    }

    public function logout()
    {
        Session::destroy();
        Flash::set(Flash::ERROR, "You are now logged out");
        redirect('/');
    }

}
