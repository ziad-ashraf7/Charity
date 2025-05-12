<?php

namespace App\controllers;

class UserDonationController extends BaseController
{
    public const BASE_ENDPOINT = '/user/donation';

    public function makeDonationView()
    {
        loadView(self::BASE_ENDPOINT . '/add', []);
    }

    public function makeDonation()
    {

    }
}