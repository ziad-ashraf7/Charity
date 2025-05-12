<?php


use App\controllers\AdminController;
use App\controllers\CampaignController;
use App\controllers\HomeController;
use App\controllers\UserController;
use App\controllers\UserDonationController;
use App\controllers\UserPaymentCardController;


# Client Endpoints

$router->get('/', [HomeController::class, 'index']);

$router->get('/user/login', [UserController::class, 'loginView']);

$router->get('/user/register', [UserController::class, 'registerView']);

$router->post('/user/signup', [UserController::class, 'signUp']);

$router->post('/user/login', [UserController::class, 'logIn']);
$router->get('/user/logout', [UserController::class, 'logout'], [\Core\System::USER]);

# user profile
$router->get('/user/profile', [UserController::class, 'profileView'], [\Core\System::USER]);
$router->post('/user/profile/personalInfo', [UserController::class, 'updatePersonalInfo'], [\Core\System::USER]);
$router->post('/user/profile/updateImg', [UserController::class, 'updateImg'], [\Core\System::USER]);
$router->post('/user/profile/updatePassword', [UserController::class, 'updatePassword'], [\Core\System::USER]);

$router->post('/user/profile/paymentMethod/add', [UserPaymentCardController::class, 'addPaymentMethod'], [\Core\System::USER]);
$router->get('/user/profile/paymentMethod/delete/{id}', [UserPaymentCardController::class, 'deletePaymentMethod'], [\Core\System::USER]);
$router->get('/user/profile/paymentMethod/setAsDefault/{id}', [UserPaymentCardController::class, 'setPaymentMethodAsDefault'], [\Core\System::USER]);

$router->get('/user/campaign/list', [CampaignController::class, 'listViewForUsers'], [\Core\System::USER]);

$router->get('/user/donation/make', [UserDonationController::class, 'makeDonationView'], [\Core\System::USER]);
$router->post('/user/donation/make', [UserDonationController::class, 'makeDonation'], [\Core\System::USER]);
$router->get('/user/donation/list', [UserDonationController::class, 'listMyDonations'], [\Core\System::USER]);



# Admin Endpoints
$router->get('/admin/createDummy', [AdminController::class, 'createDummy']);
$router->get('/admin/login', [AdminController::class, 'loginView']);
$router->post('/admin/login', [AdminController::class, 'login']);
$router->get('/admin/logout', [AdminController::class, 'logout'], [\Core\System::ADMIN]);


# Campaigns
$router->get('/admin/campaign/add', [CampaignController::class, 'addView'], [\Core\System::ADMIN]);
$router->get('/admin/campaign/list', [CampaignController::class, 'listView'], [\Core\System::ADMIN]);
$router->post('/admin/campaign/add', [CampaignController::class, 'add'], [\Core\System::ADMIN]);
$router->get('/admin/campaign/update/{id}', [CampaignController::class, 'updateView'], [\Core\System::ADMIN]);
$router->post('/admin/campaign/update/{id}', [CampaignController::class, 'update'], [\Core\System::ADMIN]);