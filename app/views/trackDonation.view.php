<?php loadPartial(\Core\System::HEADER); ?>
<body>
<!-- Preloader Start -->
<div id="preloader-active">
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-inner position-relative">
            <div class="preloader-circle"></div>
            <div class="preloader-img pere-text">
                <img src="../backend/public/assets/img/logo/logo.png" alt="">
            </div>
        </div>
    </div>
</div>
<!-- Preloader End -->

<?php loadPartial(\Core\System::NAVBAR); ?>

<main>
    <!-- Hero Area -->
    <section class="slider-area position-relative">
        <div class="slider-active">
            <!-- Single Slider -->
            <div class="single-slider">
                <div class="slider-cap-wrapper" style="margin: 200px;">
                    <div class="hero-caption">
                        <h1 data-animation="fadeInUp" data-delay=".4s">Track Your Donation</h1>
                        <p data-animation="fadeInUp" data-delay=".6s">Follow the journey of your generosity and see the
                            impact you're making</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Hero Area -->

    <!-- Donation Tracking Section -->
    <section class="donation-tracking-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-9 col-md-10">
                    <div class="card mb-5">
                        <div class="card-header">
                            <h5 class="mb-0 fw-bold">Track A Donation</h5>
                        </div>
                        <div class="card-body">
                            <!-- Search Form -->
                            <form method="get">
                                <div class="form-group">
                                    <label for="donationReference">Enter your Donation ID</label>
                                    <input type="number" class="form-control form-control-lg" id="donationReference"
                                           name="donationId"
                                           placeholder="Donation ID" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg px-4">Track Donation</button>
                            </form>

                            <!-- Tracking Result (Initially Hidden) -->
                            <div id="trackingResult" class="mt-5" style="display: none;">
                                <div class="alert alert-info mb-4">
                                    <i class="fa fa-spinner fa-spin mr-2"></i>
                                    <strong>Looking up donation details...</strong>
                                </div>

                                <div id="donationDetails" class="animate__animated animate__fadeIn">
                                    <!-- Will be populated by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Donation Impact Card -->
                    <div class="card mb-5">
                        <div class="card-header">
                            <h5 class="mb-0">Donation Dettails</h5>
                        </div>
                        <div class="card-body">
                            <div id="impactDetails" style="display: none;" class="animate__animated animate__fadeIn">
                                <!-- Will be populated by JavaScript -->
                            </div>

                            <div id="impactPlaceholder" class="text-center py-5">
                                <i class="fas fa-hand-holding-heart fa-3x text-muted mb-3"></i>
                                <?php if (isset($_GET['donationId'])): ?>
                                    <p>View Details here after performing the query</p>
                                <?php else: ?>
                                    <p class="text-muted">Track your donation above to see your impact.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Section -->
                    <!--                    <div class="card">-->
                    <!--                        <div class="card-header">-->
                    <!--                            <h5 class="mb-0 fw-bold">Frequently Asked Questions</h5>-->
                    <!--                        </div>-->
                    <!--                        <div class="card-body">-->
                    <!--                            <div class="accordion" id="trackingFAQ">-->
                    <!--                                <div class="card mb-3">-->
                    <!--                                    <div class="card-header" id="headingOne">-->
                    <!--                                        <h2 class="mb-0">-->
                    <!--                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">-->
                    <!--                                                Where can I find my donation reference?-->
                    <!--                                            </button>-->
                    <!--                                        </h2>-->
                    <!--                                    </div>-->
                    <!--                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#trackingFAQ">-->
                    <!--                                        <div class="card-body">-->
                    <!--                                            Your donation reference can be found in the confirmation email we sent when you made your donation. It usually starts with "DON-" followed by numbers.-->
                    <!--                                        </div>-->
                    <!--                                    </div>-->
                    <!--                                </div>-->
                    <!--                                <div class="card mb-3">-->
                    <!--                                    <div class="card-header" id="headingTwo">-->
                    <!--                                        <h2 class="mb-0">-->
                    <!--                                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">-->
                    <!--                                                How long does it take for my donation to be processed?-->
                    <!--                                            </button>-->
                    <!--                                        </h2>-->
                    <!--                                    </div>-->
                    <!--                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#trackingFAQ">-->
                    <!--                                        <div class="card-body">-->
                    <!--                                            Online donations are typically processed within 1-2 business days. Bank transfers may take 3-5 business days to appear in our system.-->
                    <!--                                        </div>-->
                    <!--                                    </div>-->
                    <!--                                </div>-->
                    <!--                                <div class="card">-->
                    <!--                                    <div class="card-header" id="headingThree">-->
                    <!--                                        <h2 class="mb-0">-->
                    <!--                                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">-->
                    <!--                                                I can't find my donation. What should I do?-->
                    <!--                                            </button>-->
                    <!--                                        </h2>-->
                    <!--                                    </div>-->
                    <!--                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#trackingFAQ">-->
                    <!--                                        <div class="card-body">-->
                    <!--                                            If you can't find your donation, please contact our donor support team at support@charityworks.org or call us at +1 (234) 567-8901.-->
                    <!--                                        </div>-->
                    <!--                                    </div>-->
                    <!--                                </div>-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Footer -->
<footer>
    <div class="footer-wrapper section-bg2" data-background="assets/img/gallery/footer_bg.png">
        <div class="footer-area footer-padding">
            <div class="container">
                <div class="row d-flex justify-content-between">
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                        <div class="single-footer-caption mb-50">
                            <div class="single-footer-caption mb-30">
                                <div class="footer-logo">
                                    <a href="../home.view.php"><img
                                                src="../backend/public/assets/img/logo/logo2_footer.png" alt=""></a>
                                </div>
                                <div class="footer-tittle">
                                    <div class="footer-pera">
                                        <p>Making a difference, one donation at a time.</p>
                                    </div>
                                </div>
                                <div class="footer-social">
                                    <a href="#"><i class="fab fa-twitter"></i></a>
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-pinterest-p"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Contact Info</h4>
                                <ul>
                                    <li>
                                        <p>Address: 123 Charity Street, City, Country</p>
                                    </li>
                                    <li>Phone: +1 (234) 567-8901</li>
                                    <li>Email: hello@charityworks.org</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Important Link</h4>
                                <ul>
                                    <li><a href="#"> View Project</a></li>
                                    <li><a href="#">Contact Us</a></li>
                                    <li><a href="#">Testimonial</a></li>
                                    <li><a href="#">Support</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Newsletter</h4>
                                <div class="footer-pera footer-pera2">
                                    <p>Heaven fruitful doesn't over lesser in days. Appear creeping.</p>
                                </div>
                                <!-- Form -->
                                <div class="footer-form">
                                    <div id="mc_embed_signup">
                                        <form target="_blank" action="#" method="get"
                                              class="subscribe_form relative mail_part">
                                            <input type="email" name="email" id="newsletter-form-email"
                                                   placeholder="Email Address" class="placeholder hide-on-focus"
                                                   onfocus="this.placeholder = ''"
                                                   onblur="this.placeholder = ' Email Address '">
                                            <div class="form-icon">
                                                <button type="submit" name="submit" id="newsletter-submit"
                                                        class="email_icon newsletter-submit button-contactForm"><img
                                                            src="../backend/public/assets/img/gallery/form.png" alt="">
                                                </button>
                                            </div>
                                            <div class="mt-10 info"></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom-area">
            <div class="container">
                <div class="footer-border">
                    <div class="row d-flex align-items-center justify-content-between">
                        <div class="col-lg-6">
                            <div class="footer-copy-right">
                                <p>
                                    Copyright &copy; 2023 All rights reserved | CharityWorks
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="footer-menu f-right">
                                <ul>
                                    <li><a href="#">Terms of use</a></li>
                                    <li><a href="#">Privacy Policy</a></li>
                                    <li><a href="#">Contact</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- JS here -->
<?php loadPartial(\Core\System::JS); ?>
</body>
</html>