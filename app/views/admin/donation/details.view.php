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
                                    <label for="campaign"><strong>Campaign: <?php echo $donation->name?></strong></label>
                                </div>
                                <br>
                                <!-- Donation Amount -->
                                <div class="form-group">
                                    <label><strong>Donation Amount</strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">L.E</span>
                                        </div>
                                        <input type="number" class="form-control" id="customAmount" name="amount"
                                               value="<?php echo $donation->amount ?>"
                                               min="1" placeholder="Enter amount">
                                    </div>
                                </div>

                                <!-- Donation Frequency -->
                                <div class="form-group">
                                    <label><strong>Donation Type</strong></label>
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" id="oneTime" name="donation_type"
                                               class="custom-control-input"
                                               value="<?php echo $donation->donation_tyoe ?>"
                                               checked>
                                        <label class="custom-control-label" for="oneTime">One-time donation</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="anonymousDonation" <?php echo $donation->is_anonymous ? 'checked' : ''?>
                                               name="is_anonymous">
                                        <label class="custom-control-label" for="anonymousDonation">Is Donation Anonymous
                                            </label>
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
                                    <textarea class="form-control" id="message" name="message" rows="3" disabled
                                              placeholder="Your message of support"><?php echo $donation->message ?? '' ?></textarea>
                                </div>


                                <div class="form-group mt-4">


                                    <!-- Summary -->
                                    <div class="donation-summary mt-5">
                                        <h5 class="mb-3">Donation Summary</h5>
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Donation Amount:</span>
                                                    <span id="summaryAmount"><?php echo $donation->amount?> L.E</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Processing Fee:</span>
                                                    <span id="processingFee">0.00 L.E</span>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between">
                                                    <strong>Total:</strong>
                                                    <strong id="totalAmount"><?php echo $donation->amount?> L.E</strong>
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