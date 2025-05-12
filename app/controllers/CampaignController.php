<?php

namespace App\controllers;

use App\models\CampaignModel;
use Core\FileUploader;
use Core\Flash;
use Core\Session;
use Core\System;

class CampaignController extends BaseController
{
    private CampaignModel $campaignModel;
    public const BASE_ENDPOINT = '/admin/campaign';
    public const IMG_PATH = 'campaign/pics';

    public function __construct()
    {
        $this->campaignModel = new CampaignModel();
    }

    public function addView()
    {
        loadView(self::BASE_ENDPOINT . '/add', []);
    }

    public function updateView($param)
    {
        loadView(self::BASE_ENDPOINT . '/update', [
            'campaign' => $this->campaignModel->getById($param['id'])
        ]);
    }

    public function listView()
    {
        $campaigns = $this->campaignModel->getByAdminId([
            'admin_id' => Session::get(System::USER)['id']
        ]);
        loadView(self::BASE_ENDPOINT . '/list', [
            'campaigns' => $campaigns
        ]);
    }

    public function listViewForUsers()
    {
        $campaigns = $this->campaignModel->getAll();
        loadView('/user/campaigns', [
            'campaigns' => $campaigns
        ]);
    }

    public function add()
    {
        $requiredInputs = [
            'name'
        ];
        $data = fetchFields($requiredInputs);
        $errors = checkRequiredFields($requiredInputs, $data);
        $imgRes = FileUploader::upload($_FILES['img'], self::IMG_PATH, isFileRequired: true, inputName: 'Campaign Image');
        if ($imgRes[0] === 'errors') $errors[] = $imgRes[1];
        else if ($imgRes[0] === true) $data['img'] = $imgRes[1];

        viewErrorsIfExist($errors, self::BASE_ENDPOINT . '/add');

        $data['admin_id'] = Session::get(System::ADMIN)['id'];
        $this->campaignModel->insert($data);
        Flash::set(Flash::SUCCESS, 'Campaign added successfully');
        redirect(self::BASE_ENDPOINT . '/list');
    }

    public function update($params)
    {
        $requiredInputs = [
            'name'
        ];
        $data = fetchFields($requiredInputs);
        $errors = checkRequiredFields($requiredInputs, $data);

        $oldModel = $this->campaignModel->getById($params['id']);

        $imgRes = FileUploader::upload($_FILES['img'], self::IMG_PATH, isFileRequired: false, inputName: 'Campaign Image', oldFile: $oldModel->img);


        if ($imgRes[0] === 'errors') $errors[] = $imgRes[1];
        else if ($imgRes[0] === true) $data['img'] = $imgRes[1];
        else $data['img'] = $oldModel->img;

        viewErrorsIfExist($errors, self::BASE_ENDPOINT . '/add');

        $data['admin_id'] = Session::get(System::USER)['id'];
        $this->campaignModel->update($data);

        Flash::set(Flash::SUCCESS, 'Campaign is updated successfully');
        redirect(self::BASE_ENDPOINT . '/list');
    }

    public function delete()
    {

    }
}