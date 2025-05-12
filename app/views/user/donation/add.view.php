<?php loadPartial(\Core\System::HEADER); ?>
<body>
<!-- ? Preloader Start -->
<div id="preloader-active">
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-inner position-relative">
            <div class="preloader-circle"></div>
            <div class="preloader-img pere-text">
                <img src="../backend/public/assets/img/logo/loder.png" alt="">
            </div>
        </div>
    </div>
</div>
<!-- Preloader Start -->
<?php loadPartial(\Core\System::NAVBAR); ?>
<main>
    <!-- Hero Start -->
    <div class="slider-area2">
        <div class="slider-height2 d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="hero-cap hero-cap2 pt-70 text-center">
                            <h2>Make a Donation</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Make Donation Section -->
    <section class="contact-section section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow mb-5">
                        <div class="card-header bg-white py-3">
                            <h4 class="mb-0">Your Donation</h4>
                        </div>
                        <div class="card-body">
                            <?php loadPartial(\Core\System::ERRORS); ?>
                            <form action="/user/donation/make" method="post" id="donationForm">
                                <!-- Campaign Selection -->
                                <div class="form-group">
                                    <label for="campaign"><strong>Choose Campaign</strong></label>
                                    <select class="form-control nice-select" id="campaign" name="campaign_id">
                                        <?php foreach ($campaigns as $campaign): ?>
                                            <option value="<?php echo $campaign->id ?>"><?php echo $campaign->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <br>
                                <!-- Donation Amount -->
                                <div class="form-group">
                                    <label><strong>Donation Amount</strong></label>
                                    <div class="donation-amount-buttons mb-3">
                                        <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                            <label class="btn btn-outline-primary">
                                                <input type="radio" name="donationAmount" value="10"> 10 L.E
                                            </label>
                                            <label class="btn btn-outline-primary">
                                                <input type="radio" name="donationAmount" value="25"> 25 L.E
                                            </label>
                                            <label class="btn btn-outline-primary">
                                                <input type="radio" name="donationAmount" value="50"> 50 L.E
                                            </label>
                                            <label class="btn btn-outline-primary">
                                                <input type="radio" name="donationAmount" value="100"> 100 L.E
                                            </label>
                                            <label class="btn btn-outline-primary active">
                                                <input type="radio" name="donationAmount" value="other" checked> Other
                                            </label>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">L.E</span>
                                        </div>
                                        <input type="number" class="form-control" id="customAmount" name="amount"
                                               min="1" placeholder="Enter amount">
                                    </div>
                                </div>

                                <!-- Donation Frequency -->
                                <div class="form-group">
                                    <label><strong>Donation Type</strong></label>
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" id="oneTime" name="donation_type"
                                               class="custom-control-input"
                                               value="<?php echo \App\Enum\DonationTypeEnum::ONE_TIME->value ?>"
                                               checked>
                                        <label class="custom-control-label" for="oneTime">One-time donation</label>
                                    </div>
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" id="monthly" name="donation_type"
                                               class="custom-control-input"
                                               value="<?php echo \App\Enum\DonationTypeEnum::MONTHLY_RECURRING->value ?>">
                                        <label class="custom-control-label" for="monthly">Monthly recurring</label>
                                    </div>
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" id="quarterly" name="donation_type"
                                               class="custom-control-input"
                                               value="<?php echo \App\Enum\DonationTypeEnum::QUARTERLY_RECURRING->value ?>">
                                        <label class="custom-control-label" for="quarterly">Quarterly recurring</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="annually" name="donation_type"
                                               class="custom-control-input"
                                               value="<?php echo \App\Enum\DonationTypeEnum::ANNUALLY_RECURRING->value ?>">
                                        <label class="custom-control-label" for="annually">Annual recurring</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="anonymousDonation"
                                               name="is_anonymous">
                                        <label class="custom-control-label" for="anonymousDonation">Make this donation
                                            anonymous</label>
                                    </div>
                                </div>

                                <!-- Donor Info Section -->
                                <h5 class="mt-5 mb-4">Donor Information</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="firstName">First Name</label>
                                            <input type="text" class="form-control" id="firstName" name="firstName"
                                                   value="<?php echo $user->first_name ?>"
                                                   disabled
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lastName">Last Name</label>
                                            <input type="text" class="form-control" id="lastName" name="lastName"
                                                   value="<?php echo $user->last_name ?>"
                                                   disabled
                                                   required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           disabled
                                           value="<?php echo $user->email ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="tel" class="form-control" id="phone" value="<?php echo $user->phone ?>"
                                           disabled
                                           name="phone">
                                </div>

                                <!-- Leave a Message -->
                                <div class="form-group">
                                    <label for="message">Leave a Message (Optional)</label>
                                    <textarea class="form-control" id="message" name="message" rows="3"
                                              placeholder="Your message of support"></textarea>
                                </div>

                                <!-- Payment Method Section -->
                                <h5 class="mt-5 mb-4">Payment Method</h5>
                                <ul class="nav nav-tabs" id="paymentMethodTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="creditcard-tab" data-toggle="tab"
                                           href="#creditcard" role="tab" aria-controls="creditcard"
                                           aria-selected="true">Credit Card</a>
                                    </li>
                                </ul>
                                <div class="tab-content mt-3" id="paymentMethodContent">
                                    <div class="tab-pane fade show active" id="creditcard" role="tabpanel"
                                         aria-labelledby="creditcard-tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="cardNumber">Card Number</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control"
                                                               hidden
                                                               name="payment_card_id"
                                                               value="<?php echo $paymentCard->id ?? null ?>">

                                                        <input type="text" class="form-control" id="cardNumber"
                                                               name="card_number"
                                                               value="<?php echo $paymentCard->card_number ?? null ?>"
                                                               placeholder="1234 5678 9012 3456">
                                                        <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fab fa-cc-visa mr-1"></i>
                                                                    <i class="fab fa-cc-mastercard mr-1"></i>
                                                                    <i class="fab fa-cc-amex"></i>
                                                                </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="expiryDate">Expiry Date</label>
                                                    <input type="text" class="form-control" id="expiryDate"
                                                           name="expiration_date"
                                                           value="<?php echo $paymentCard->expiration_date ?? null ?>"
                                                           placeholder="MM/YY">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="cvv">CVV</label>
                                                    <input type="text" class="form-control" id="cvv" name="cvv"
                                                           placeholder="123">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="nameOnCard">Name on Card</label>
                                            <input type="text" class="form-control" name="cardholder_name"
                                                   value="<?php echo $paymentCard->cardholder_name ?? null ?>"
                                                   id="nameOnCard">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-4">


                                    <!-- Summary -->
                                    <div class="donation-summary mt-5">
                                        <h5 class="mb-3">Donation Summary</h5>
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Donation Amount:</span>
                                                    <span id="summaryAmount">L.E0.00</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Processing Fee:</span>
                                                    <span id="processingFee">L.E0.00</span>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between">
                                                    <strong>Total:</strong>
                                                    <strong id="totalAmount">L.E0.00</strong>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <button type="submit" class="btn btn-primary btn-lg btn-block mt-5">Complete
                                        Donation
                                    </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Make Donation Section End -->
</main>
<?php loadPartial(\Core\System::FOOTER); ?>

<!-- Scroll Up -->
<div id="back-top">
    <a title="Go to Top" href="#"> <i class="fas fa-level-up-alt"></i></a>
</div>

<!-- JS here -->
<?php loadPartial(\Core\System::JS); ?>
</body>
</html>