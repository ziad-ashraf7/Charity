<?php

namespace App\controllers;

use App\models\AdminModel;
use App\models\UserPaymentCardModel;
use Core\Flash;
use Core\Session;

class UserPaymentCardController extends BaseController
{
    private UserPaymentCardModel $userPaymentCardModel;

    public function __construct()
    {
        parent::__construct();
        $this->userPaymentCardModel = new UserPaymentCardModel();
    }

    public function paymentMehtodInputsValidation(&$data, &$errors)
    {
        if (!preg_match('/^([0-1][1-9]|1[0-2])\/\d{2}$/', $data['expiration_date']))
            $errors['expiration_date'] = 'Invalid expiration date, must be mm/yy';

        if (!preg_match('/^\d{13,19}$/', $data['card_number']))
            $errors['card_number'] = 'Invalid card number, must be between 13,19 numbers';

        if (strlen($data['cvv']) != 3)
            $errors['cvv'] = 'Invalid cvv, must be 3 digits';
    }

    public function addPaymentMethod()
    {
        $requiredFields = [
            'cardholder_name',
            'card_number',
            'expiration_date',
            'cvv',
            ['is_default', false]
        ];
        $data = fetchFields($requiredFields);
        $errors = checkRequiredFields($requiredFields, $data);
        //inspectAndDie($errors, $data);

        $this->paymentMehtodInputsValidation($data, $errors);

        viewErrorsIfExist($errors, '/user/profile');

        $userId = Session::get('user')['id'];
        $data['user_id'] = $userId;
        $data['is_default'] = isset($data['is_default']);
        $this->userPaymentCardModel->insert($data);

        Flash::set(Flash::SUCCESS, 'Payment method is added successfully');
        redirect('/user/profile');
    }

    public function deletePaymentMethod($params): void
    {
        #TODO: handle case if it is associated with a donation
        $this->userPaymentCardModel->delete($params['id']);
        Flash::set(Flash::SUCCESS, 'Payment method is deleted successfully');
        redirect('/user/profile');
    }

    public function setPaymentMethodAsDefault($params): void
    {
        $this->userPaymentCardModel->setAsDefault(Session::get('user')['id'], $params['id']);
        Flash::set(Flash::SUCCESS, 'Payment method is set as default successfully');
        redirect('/user/profile');
    }
}