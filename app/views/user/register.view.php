<?php loadPartial('header'); ?>
<?php loadPartial('success'); ?>
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
<?php loadPartial('navBar'); ?>

<main>
    <!--? Hero Start -->
    <div class="slider-area2">
        <div class="slider-height2 d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="hero-cap hero-cap2 text-center">
                            <h2>Register</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Register Form Start -->
    <section class="contact-section section-padding30">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section-tittle text-center mb-30">
                        <span>Create your account</span>
                        <h2>Join Our Community</h2>
                        <p>Register to donate, join campaigns, and track your contributions</p>
                    </div>
                    <?php loadPartial('errors'); ?>
                    <form class="form-contact" action="/user/signup" method="post">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" name="first_name" id="firstName" type="text"
                                           onfocus="this.placeholder = ''" onblur="this.placeholder = 'First Name'"
                                           placeholder="First Name">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" name="last_name" id="lastName" type="text"
                                           onfocus="this.placeholder = ''" onblur="this.placeholder = 'Last Name'"
                                           placeholder="Last Name">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control" name="email" id="email" type="email"
                                           onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email Address'"
                                           placeholder="Email Address">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control" name="phone" id="phoneNumber" type="tel"
                                           onfocus="this.placeholder = ''" onblur="this.placeholder = 'Phone Number'"
                                           placeholder="Phone Number">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" name="password" id="password" type="password"
                                           onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'"
                                           placeholder="Password">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" name="confirm_password" id="confirmPassword"
                                           type="password" onfocus="this.placeholder = ''"
                                           onblur="this.placeholder = 'Confirm Password'"
                                           placeholder="Confirm Password">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mt-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="termsCheck" required>
                                        <label class="form-check-label" for="termsCheck">I agree to the <a href="#"
                                                                                                           class="text-primary">Terms
                                                of Service</a> and <a href="#" class="text-primary">Privacy
                                                Policy</a></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-30 text-center">
                            <button type="submit" class="button button-contactForm boxed-btn">Register</button>
                        </div>
                        <div class="form-group text-center mt-20">
                            <p>Already have an account? <a href="/user/login" class="text-primary">Login here</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Register Form End -->
</main>

<?php loadPartial('footer'); ?>

<!-- Scroll Up -->
<div id="back-top">
    <a title="Go to Top" href="#"> <i class="fas fa-level-up-alt"></i></a>
</div>

<!-- JS here -->
<?php loadPartial('js'); ?>
</body>
</html>