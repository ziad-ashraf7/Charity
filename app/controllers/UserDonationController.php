<?php

namespace App\controllers;

use App\Enum\DonationTypeEnum;
use App\models\CampaignModel;
use App\models\DonationModel;
use App\models\UserModel;
use App\models\UserPaymentCardModel;
use Core\Database;
use Core\Flash;
use Core\Session;

class UserDonationController extends BaseController
{
    public const BASE_ENDPOINT = '/user/donation';
    private CampaignModel $campaignModel;
    private UserPaymentCardModel $userPaymentCardModel;
    private UserModel $userModel;
    private DonationModel $donationModel;
    private int $userId;

    public function __construct()
    {
        parent::__construct();
        $this->campaignModel = new CampaignModel();
        $this->userModel = new UserModel();
        $this->userPaymentCardModel = new UserPaymentCardModel();
        $this->donationModel = new DonationModel();
        $this->userId = Session::get('user')['id'];
    }

    public function makeDonationView()
    {
        $campaigns = $this->campaignModel->getAll();
        $user = $this->userModel->getUserById($this->userId);
        $userDefaultPaymentCard = $this->userPaymentCardModel->getDefaultCardForUser($this->userId);
        loadView(self::BASE_ENDPOINT . '/add', [
            'campaigns' => $campaigns,
            'user' => $user,
            'paymentCard' => $userDefaultPaymentCard
        ]);
    }

    public function makeDonation()
    {
        $requiredFields = [
            'campaign_id',
            'amount',
            'donation_type',
            ['is_anonymous', false],
            ['message', false],
            'cardholder_name',
            'card_number',
            'expiration_date',
            ['payment_card_id', false],
            'cvv',
        ];

        $data = fetchFields($requiredFields);
        $errors = checkRequiredFields($requiredFields, $data);

        (new UserPaymentCardController())->paymentMehtodInputsValidation($data, $errors);
        if ($data['payment_card_id'] != '' && !$this->userPaymentCardModel->isCvvSame($data['payment_card_id'], $data['cvv']))
            $errors['cvv'] = 'CVV number is wrong please try again';

        viewErrorsIfExist($errors, '/user/donation/make');

        $data['user_id'] = $this->userId;
        $data['is_default'] = 0;

        try {
            Database::getInstance()->beginTransaction();
            if ($data['payment_card_id'] == '') {
                $this->userPaymentCardModel->insert([
                    'cardholder_name' => $data['cardholder_name'],
                    'card_number' => $data['card_number'],
                    'expiration_date' => $data['expiration_date'],
                    'cvv' => $data['cvv'],
                    'is_default' => $data['is_default'],
                    'user_id' => $data['user_id'],
                ]);
                $data['payment_card_id'] = $this->userPaymentCardModel->getUserLastCard($this->userId)->id;
            }
            $data['user_id'] = $this->userId;
            $data['is_anonymous'] = isset($data['is_anonymous']);
            //inspectAndDie($data);
            $this->donationModel->addDonation([
                'user_id' => $data['user_id'],
                'campaign_id' => $data['campaign_id'],
                'payment_card_id' => $data['payment_card_id'],
                'amount' => $data['amount'],
                'donation_type' => $data['donation_type'],
                'is_anonymous' => $data['is_anonymous'],
                'message' => $data['message'],
            ]);
            Database::getInstance()->commit();
        } catch (\Exception $e) {
            Database::getInstance()->rollBack();
            inspectAndDie($e->getMessage());
        }
        //inspectAndDie("Done");
        Flash::set(Flash::SUCCESS, 'Donation was successfully made');
        redirect(self::BASE_ENDPOINT . '/list');
    }

    public function listMyDonations()
    {
        $campaigns = $this->campaignModel->getAll();
        loadView(self::BASE_ENDPOINT . '/list', [
            'donations' => $this->donationModel->getUserDonation($this->userId),
            'campaigns' => $campaigns,
            'donationTypes' => DonationTypeEnum::cases(),
            'totalDonations' => $this->donationModel->getTotalDonations($this->userId),
            'totalDonationNumber' => $this->donationModel->getTotalDonationNumber($this->userId),
            'activeRecurring' => $this->donationModel->getActiveRecurring($this->userId),
            'supportedCampaigns' => $this->donationModel->getSupportedCampaigns($this->userId)
        ]);
    }

    public function makeDonationInActive($params)
    {
        $this->donationModel->markInActive($params['id']);
        Flash::set(Flash::SUCCESS, 'Donation is marked inactive successfully');
        redirect(self::BASE_ENDPOINT . '/list');
    }

    public function viewDetails($params)
    {
        $campaigns = $this->campaignModel->getAll();
        $user = $this->userModel->getUserById($this->userId);
        $donation = $this->donationModel->getByIdWithJoin($params['id']);
        loadView(self::BASE_ENDPOINT . '/details', [
            'donation' => $donation,
            'campaigns' => $campaigns,
            'user' => $user,
        ]);
    }
}