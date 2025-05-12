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
    <!--? Hero Start -->
    <div class="slider-area2">
        <div class="slider-height2 d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="hero-cap hero-cap2 text-center">
                            <h2>Edit Profile</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->
    <?php loadPartial(\Core\System::SUCCESS) ?>
    <?php loadPartial(\Core\System::ERRORS) ?>

    <!-- Edit Profile Section Start -->
    <section class="contact-section section-padding30">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="profile-card text-center">
                        <form action="/user/profile/updateImg" method="post" enctype="multipart/form-data">
                            <div class="profile-img-container">
                                <img width="60%"
                                     src="<?php echo getImage(\App\controllers\UserController::IMG_PATH, $user->img) ?>"
                                     alt="Profile Image" class="profile-img">
                                <label for="profile-img-upload" class="edit-img-btn">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input required type="file" name="img" id="profile-img-upload" style="display: none;"
                                       accept="image/*">
                                <button type="submit"> update image</button>
                            </div>
                        </form>
                        <div class="mt-3">
                            <h4><?php echo $user->first_name . ' ' . $user->last_name ?></h4>
                        </div>
                        <div class="mt-4">
                            <a href="profile.html" class="btn btn-outline-primary btn-block">View Profile</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="profile-card">
                        <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-personal-tab" data-toggle="pill"
                                   href="#pills-personal" role="tab" aria-controls="pills-personal"
                                   aria-selected="true">Personal Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-security-tab" data-toggle="pill" href="#pills-security"
                                   role="tab" aria-controls="pills-security" aria-selected="false">Security</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="pills-payment-tab" data-toggle="pill" href="#pills-payment"
                                   role="tab" aria-controls="pills-payment" aria-selected="false">Payment Methods</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <!-- Personal Information Tab -->
                            <div class="tab-pane fade show active" id="pills-personal" role="tabpanel"
                                 aria-labelledby="pills-personal-tab">
                                <h4 class="mb-4">Personal Information</h4>
                                <form id="personalInfoForm" action="/user/profile/personalInfo" method="post">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="firstName">First Name</label>
                                                <input type="text" class="form-control" id="first_name"
                                                       name="first_name"
                                                       value="<?php echo $user->first_name ?>"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="lastName">Last Name</label>
                                                <input type="text" class="form-control" id="last_name"
                                                       name="last_name"
                                                       value="<?php echo $user->last_name ?>"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" class="form-control" id="email"
                                               name="email"
                                               value="<?php echo $user->email ?>"
                                               required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phoneNumber">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone"
                                               name="phone"
                                               value="<?php echo $user->phone ?>"
                                               required>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea class="form-control" id="address"
                                                  name="address"
                                                  rows="3"><?php echo $user->address ?? '' ?></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="city">City</label>
                                                <input type="text" class="form-control" id="city"
                                                       name="city"
                                                       value="<?php echo $user->city ?? '' ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="country">Country</label>
                                                <select class="form-control nice-select" name="country" id="country">
                                                    <option value="Egypt" <?php echo $user->country == 'Egypt' ? 'selected' : '' ?>
                                                    >Egypt
                                                    </option>
                                                    <option value="Saudi Arabia" <?php echo $user->country == 'Saudi Arabia' ? 'selected' : '' ?>>
                                                        Saudi Arabia
                                                    </option>
                                                    <option value="United Arab Emirates" <?php echo $user->country == 'United Arab Emirates' ? 'selected' : '' ?>>
                                                        United Arab Emirates
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="button button-contactForm boxed-btn">Save Changes
                                    </button>
                                </form>
                            </div>

                            <!-- Security Tab -->
                            <div class="tab-pane fade" id="pills-security" role="tabpanel"
                                 aria-labelledby="pills-security-tab">
                                <h4 class="mb-4">Security Settings</h4>
                                <form id="securityForm" method="post" action="/user/profile/updatePassword">
                                    <div class="form-group">
                                        <label for="currentPassword">Current Password</label>
                                        <input type="password" class="form-control" id="currentPassword"
                                               name="current_password"
                                               placeholder="Enter current password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="newPassword">New Password</label>
                                        <input type="password" class="form-control" id="newPassword"
                                               name="new_password"
                                               placeholder="Enter new password">
                                    </div>
                                    <div class="form-group">
                                        <label for="confirmPassword">Confirm New Password</label>
                                        <input type="password" class="form-control" id="confirmPassword"
                                               name="confirm_password"
                                               placeholder="Confirm new password">
                                    </div>
                                    <button type="submit" class="button button-contactForm boxed-btn">Update Password
                                    </button>
                                </form>
                            </div>

                            <!-- Payment Methods Tab -->
                            <div class="tab-pane fade" id="pills-payment" role="tabpanel"
                                 aria-labelledby="pills-payment-tab">
                                <h4 class="mb-4">Payment Methods</h4>
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title">Saved Payment Methods</h5>
                                        <?php foreach ($paymentMethods as $paymentMethod): ?>
                                            <div class="row align-items-center mb-3 p-3 border rounded">
                                                <div class="col-auto">
                                                    <i class="fab fa-cc-mastercard fa-2x text-warning"></i>
                                                </div>
                                                <div class="col">
                                                    <span class="font-weight-bold"><?php echo str_repeat("**** ", ((strlen($paymentMethod->card_number) - 4) / 3)) . substr($paymentMethod->card_number, -4) ?></span>
                                                    <br>
                                                    <small class="text-muted">Expires <?php echo $paymentMethod->expiration_date ?></small>
                                                </div>
                                                <?php if ($paymentMethod->is_default): ?>
                                                    <div class="col-auto">
                                                        <span class="badge badge-success">Default</span>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="col-auto">
                                                        <button class="btn btn-sm btn-outline-primary">
                                                            <a href="/user/profile/paymentMethod/setAsDefault/<?php echo $paymentMethod->id ?>">Set Default</a>
                                                        </button>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="col-auto">
                                                    <button class="btn btn-sm btn-outline-danger">
                                                        <a href="/user/profile/paymentMethod/delete/<?php echo $paymentMethod->id ?>">Remove</a>
                                                    </button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Add New Payment Method</h5>
                                        <form id="paymentMethodForm" method="post"
                                              action="/user/profile/paymentMethod/add">
                                            <div class="form-group">
                                                <label for="cardholderName">Cardholder Name</label>
                                                <input type="text" class="form-control" id="cardholderName"
                                                       name="cardholder_name"
                                                       placeholder="Name on card">
                                            </div>
                                            <div class="form-group">
                                                <label for="cardNumber">Card Number</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="cardNumber"
                                                           name="card_number"
                                                           placeholder="1234 5678 9012 3456">
                                                    <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <i class="far fa-credit-card"></i>
                                                            </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="expiryDate">Expiration Date</label>
                                                        <input type="text" class="form-control" id="expiryDate"
                                                               name="expiration_date"
                                                               placeholder="MM/YY">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="cvv">CVV</label>
                                                        <input type="text" class="form-control" id="cvv"
                                                               name="cvv"
                                                               placeholder="123">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" name="is_default" type="checkbox"
                                                       id="setAsDefault">
                                                <label class="form-check-label" for="setAsDefault">
                                                    Set as default payment method
                                                </label>
                                            </div>
                                            <button type="submit" class="button button-contactForm boxed-btn">Add
                                                Payment Method
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Edit Profile Section End -->
</main>
<footer>
    <div class="footer-wrapper section-bg2" data-background="assets/img/gallery/footer_bg.png">
        <!-- Footer Top-->
        <div class="footer-area footer-padding">
            <div class="container">
                <div class="row d-flex justify-content-between">
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                        <div class="single-footer-caption mb-50">
                            <div class="single-footer-caption mb-30">
                                <div class="footer-tittle">
                                    <div class="footer-logo mb-20">
                                        <a href="../home.view.php"><img
                                                    src="../backend/public/assets/img/logo/logo2_footer.png" alt=""></a>
                                    </div>
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
                                        <p>Address: Your address goes here, your demo address.</p>
                                    </li>
                                    <li><a href="#">Phone: +8880 44338899</a></li>
                                    <li><a href="#">Email: info@colorlib.com</a></li>
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
                                        <form target="_blank"
                                              action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01"
                                              method="get" class="subscribe_form relative mail_part">
                                            <input type="email" name="email" id="newsletter-form-email"
                                                   placeholder="Email Address"
                                                   class="placeholder hide-on-focus" onfocus="this.placeholder = ''"
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
        <!-- footer-bottom -->
        <div class="footer-bottom-area">
            <div class="container">
                <div class="footer-border">
                    <div class="row d-flex justify-content-between align-items-center">
                        <div class="col-xl-10 col-lg-9 ">

                        </div>
                        <div class="col-xl-2 col-lg-3">
                            <div class="footer-social f-right">
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fas fa-globe"></i></a>
                                <a href="#"><i class="fab fa-behance"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Scroll Up -->
<div id="back-top">
    <a title="Go to Top" href="#"> <i class="fas fa-level-up-alt"></i></a>
</div>

<?php loadPartial(\Core\System::JS); ?>

</body>
</html>