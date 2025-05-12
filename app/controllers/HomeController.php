<?php

namespace App\controllers;

use App\models\DonationModel;

class HomeController extends BaseController
{
    private DonationModel $donationModel;

    public function __construct()
    {
        parent::__construct();
        $this->donationModel = new DonationModel();
    }

    public function index()
    {
        loadView('home', [
            'totalDonations' => $this->donationModel->getTotalDonations(),
            'totalDonationNumber' => $this->donationModel->getTotalDonationNumber(),
            'totalCampaigns' => $this->donationModel->getSupportedCampaigns()
        ]);
    }
    public function trackDonationView()
    {
        loadView('trackDonation');
    }
}