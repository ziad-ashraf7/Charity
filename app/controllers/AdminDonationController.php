<?php

namespace App\controllers;

use App\Enum\DonationTypeEnum;
use App\models\CampaignModel;
use App\models\DonationModel;
use App\models\UserModel;

class AdminDonationController extends BaseController
{
    public const BASE_ENDPOINT = '/admin/donation';
    private DonationModel $donationModel;
    private CampaignModel $campaignModel;
    private UserModel $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->donationModel = new DonationModel();
        $this->campaignModel = new CampaignModel();
        $this->userModel = new UserModel();
    }

    public function listView()
    {
        $campaigns = $this->campaignModel->getAll();
        loadView(self::BASE_ENDPOINT . '/list', [
            'donations' => $this->donationModel->getAllDonations(),
            'campaigns' => $campaigns,
            'donationTypes' => DonationTypeEnum::cases(),
            'totalDonations' => $this->donationModel->getTotalDonations(),
            'totalDonationNumber' => $this->donationModel->getTotalDonationNumber(),
            'activeRecurring' => $this->donationModel->getActiveRecurring(),
            'supportedCampaigns' => $this->donationModel->getSupportedCampaigns()
        ]);
    }
    public function viewDetails($params)
    {
        $donation = $this->donationModel->getByIdWithJoin($params['id']);
        $user = $this->userModel->getUserById($donation->user_id);
        loadView(self::BASE_ENDPOINT . '/details', [
            'donation' => $donation,
            'user' => $user,
        ]);
    }
}