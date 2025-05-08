<?php
// Start session to store calculation results if needed
session_start();

// Initialize variables
$assets = $gold = $silver = $cash = $stocks = $property = $debts = $liabilities = $zakat = 0;
$result = $errors = array();
$calculated = false;

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $gold = filter_input(INPUT_POST, 'gold', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?: 0;
    $silver = filter_input(INPUT_POST, 'silver', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?: 0;
    $cash = filter_input(INPUT_POST, 'cash', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?: 0;
    $stocks = filter_input(INPUT_POST, 'stocks', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?: 0;
    $property = filter_input(INPUT_POST, 'property', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?: 0;
    $debts = filter_input(INPUT_POST, 'debts', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?: 0;
    $liabilities = filter_input(INPUT_POST, 'liabilities', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?: 0;

    // Validate inputs for negative values
    if ($gold < 0 || $silver < 0 || $cash < 0 || $stocks < 0 || $property < 0 || $debts < 0 || $liabilities < 0) {
        $errors[] = "Values cannot be negative.";
    }

    if (empty($errors)) {
        // Calculate total assets
        $assets = $gold + $silver + $cash + $stocks + $property;
        
        // Calculate total liabilities
        $total_liabilities = $debts + $liabilities;
        
        // Calculate net zakatable assets
        $net_assets = $assets - $total_liabilities;
        
        // Calculate Zakat (2.5% of net assets)
        if ($net_assets > 0) {
            $zakat = $net_assets * 0.025;
            $calculated = true;
        } else {
            $result['message'] = "Your net assets are below the threshold for Zakat.";
        }
    } else {
        $result['message'] = implode(" ", $errors);
    }
}
?>
<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Zakah Calculator | CharityWorks</title>
    <meta name="description" content="Calculate your Zakah (Islamic alms) easily">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="site.webmanifest">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

    <!-- CSS here -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/slicknav.css">
    <link rel="stylesheet" href="assets/css/flaticon.css">
    <link rel="stylesheet" href="assets/css/progressbar_barfiller.css">
    <link rel="stylesheet" href="assets/css/gijgo.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/animated-headline.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/slick.css">
    <link rel="stylesheet" href="assets/css/nice-select.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- ? Preloader Start -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <img src="assets/img/logo/loder.png" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader Start -->
    <header>
        <!-- Header Start -->
        <div class="header-area">
            <div class="main-header ">
                <div class="header-top d-none d-lg-block">
                    <div class="container-fluid">
                        <div class="col-xl-12">
                            <div class="row d-flex justify-content-between align-items-center">
                                <div class="header-info-left d-flex">
                                    <ul>     
                                        <li>Phone: +99 (0) 101 0000 888</li>
                                        <li>Email: noreply@yourdomain.com</li>
                                    </ul>
                                    <div class="header-social">    
                                        <ul>
                                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                            <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                                            <li><a href="#"><i class="fab fa-google-plus-g"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="header-info-right d-flex align-items-center">
                                    <div class="select-this">
                                        <form action="#">
                                            <div class="select-itms">
                                                <select name="select" id="select1" onchange="changeLanguage(this.value)">
                                                    <option value="en">English</option>
                                                    <option value="ar">Arabic</option>
                                                    <option value="bn">Bangla</option>
                                                    <option value="hi">Hindi</option>
                                                </select>
                                            </div>
                                        </form>
                                    </div>
                                    <ul class="contact-now">     
                                        <li><a href="/charityworks-master/login.php">Login</a></li>
                                        <li><a href="/charityworks-master/register.html">Register</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-bottom header-sticky">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <!-- Logo -->
                            <div class="col-xl-2 col-lg-2">
                                <div class="logo">
                                    <a href="index.html"><img src="assets/img/logo/logo.png" alt=""></a>
                                </div>
                            </div>
                            <div class="col-xl-10 col-lg-10">
                                <div class="menu-wrapper d-flex align-items-center justify-content-end">
                                    <!-- Main-menu -->
                                    <div class="main-menu d-none d-lg-block">
                                        <nav>
                                            <ul id="navigation">                                                                                          
                                                <li><a href="index.html">Home</a></li>
                                                <li><a href="about.html">About</a></li>
                                                <li><a href="program.html">Latest Causes</a></li>
                                                <li><a href="events.html">Events</a></li>
                                                <li><a href="#">Donation</a>
                                                    <ul class="submenu">
                                                        <li><a href="make-donation.html">Make Donation</a></li>
                                                        <li><a href="donation-history.html">Donation History</a></li>
                                                        <li><a href="zakah-calculator.php">Zakah Calculator</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="blog.html">Blog</a>
                                                    <ul class="submenu">
                                                        <li><a href="blog.html">Blog</a></li>
                                                        <li><a href="blog_details.html">Blog Details</a></li>
                                                        <li><a href="elements.html">Element</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="#">Account</a>
                                                    <ul class="submenu">
                                                        <li><a href="profile.html">My Profile</a></li>
                                                        <li><a href="edit-profile.html">Edit Profile</a></li>
                                                        <li><a href="logout.html">Logout</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="contact.html">Contact</a></li>
                                            </ul>
                                        </nav>
                                    </div>
                                    <!-- Header-btn -->
                                    <div class="header-right-btn d-none d-lg-block ml-20">
                                        <a href="make-donation.html" class="btn header-btn">Donate</a>
                                    </div>
                                </div>
                            </div> 
                            <!-- Mobile Menu -->
                            <div class="col-12">
                                <div class="mobile_menu d-block d-lg-none"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Header End -->
    </header>
    <main>
        <!-- Hero Start -->
        <div class="slider-area2">
            <div class="slider-height2 d-flex align-items-center">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="hero-cap hero-cap2 pt-70 text-center">
                                <h2>Zakah Calculator</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hero End -->

        <!-- Zakah Calculator Section -->
        <section class="contact-section section-padding">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card shadow mb-5">
                            <div class="card-header bg-white py-3">
                                <h4 class="mb-0">Calculate Your Zakah</h4>
                            </div>
                            <div class="card-body">
                                <div class="zakah-guide mb-4">
                                    <div class="alert alert-info">
                                        <h5 class="alert-heading"><i class="fas fa-info-circle mr-2"></i>What is Zakah?</h5>
                                        <p>Zakah is one of the five pillars of Islam. It is a form of obligatory charity that has the potential to ease the suffering of millions. With the annual giving of 2.5% of one's wealth, Zakah redistributes wealth in society and helps lift people out of poverty.</p>
                                        <hr>
                                        <p class="mb-0">Our calculator helps you determine the amount of Zakah you should pay based on your assets and liabilities.</p>
                                    </div>
                                </div>

                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="zakahCalculatorForm">
                                    <div class="form-section">
                                        <h5 class="mb-3">Your Assets</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="gold">Gold Value ($)</label>
                                                    <input type="number" step="0.01" min="0" class="form-control" id="gold" name="gold" value="<?php echo $gold; ?>" placeholder="Value of gold owned">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="silver">Silver Value ($)</label>
                                                    <input type="number" step="0.01" min="0" class="form-control" id="silver" name="silver" value="<?php echo $silver; ?>" placeholder="Value of silver owned">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="cash">Cash & Bank Balances ($)</label>
                                            <input type="number" step="0.01" min="0" class="form-control" id="cash" name="cash" value="<?php echo $cash; ?>" placeholder="Cash in hand and bank accounts">
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="stocks">Stocks & Investments ($)</label>
                                                    <input type="number" step="0.01" min="0" class="form-control" id="stocks" name="stocks" value="<?php echo $stocks; ?>" placeholder="Value of investments">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="property">Business Assets ($)</label>
                                                    <input type="number" step="0.01" min="0" class="form-control" id="property" name="property" value="<?php echo $property; ?>" placeholder="Business assets value">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-section mt-4">
                                        <h5 class="mb-3">Your Liabilities</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="debts">Debts Owed ($)</label>
                                                    <input type="number" step="0.01" min="0" class="form-control" id="debts" name="debts" value="<?php echo $debts; ?>" placeholder="Money you owe to others">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="liabilities">Other Liabilities ($)</label>
                                                    <input type="number" step="0.01" min="0" class="form-control" id="liabilities" name="liabilities" value="<?php echo $liabilities; ?>" placeholder="Other liabilities">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary btn-lg btn-block mt-4">Calculate Zakah</button>
                                </form>

                                <?php if ($calculated): ?>
                                <div class="zakah-result mt-5">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="text-center mb-4">Your Zakah Calculation</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="calculation-item d-flex justify-content-between mb-2">
                                                        <span>Total Assets:</span>
                                                        <strong>$<?php echo number_format($assets, 2); ?></strong>
                                                    </div>
                                                    <div class="calculation-item d-flex justify-content-between mb-2">
                                                        <span>Total Liabilities:</span>
                                                        <strong>$<?php echo number_format($debts + $liabilities, 2); ?></strong>
                                                    </div>
                                                    <div class="calculation-item d-flex justify-content-between">
                                                        <span>Net Zakatable Assets:</span>
                                                        <strong>$<?php echo number_format($net_assets, 2); ?></strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="zakah-amount text-center p-3 bg-white rounded">
                                                        <h6 class="mb-2">Your Zakah Amount</h6>
                                                        <div class="display-4 text-primary">$<?php echo number_format($zakat, 2); ?></div>
                                                        <small class="text-muted">(2.5% of net assets)</small>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="text-center mt-4">
                                                <a href="make-donation.html" class="btn btn-primary">Pay Your Zakah Now</a>
                                                <button onclick="window.print()" class="btn btn-outline-secondary ml-2"><i class="fas fa-print mr-2"></i>Print Calculation</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                                <div class="alert alert-info mt-4">
                                    <p class="mb-0"><?php echo $result['message']; ?></p>
                                </div>
                                <?php endif; ?>

                                <div class="zakah-info mt-5">
                                    <div class="accordion" id="zakahAccordion">
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                        Who should pay Zakah?
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#zakahAccordion">
                                                <div class="card-body">
                                                    Zakah is obligatory upon adult Muslims of sound mind who possess wealth equal to or exceeding the nisab (minimum threshold) for a complete lunar year. The nisab is equivalent to approximately 87.48 grams of gold or 612.36 grams of silver.
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="card">
                                            <div class="card-header" id="headingTwo">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                        What wealth is Zakah payable on?
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#zakahAccordion">
                                                <div class="card-body">
                                                    Zakah is payable on gold, silver, cash, business assets, stocks, agricultural produce, livestock, and minerals. Personal items such as your primary residence, car for personal use, furniture, and clothing are not subject to Zakah.
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="card">
                                            <div class="card-header" id="headingThree">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                        When should Zakah be paid?
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#zakahAccordion">
                                                <div class="card-body">
                                                    Zakah should be paid once a year on wealth that has been in possession for one lunar year. Many Muslims choose to pay during Ramadan as good deeds are particularly rewarded during this blessed month, but it can be paid at any time of the year.
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="card">
                                            <div class="card-header" id="headingFour">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                        Who can receive Zakah?
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#zakahAccordion">
                                                <div class="card-body">
                                                    The Quran specifies eight categories of people eligible to receive Zakah: the poor, the needy, those employed to administer the funds, those whose hearts have been recently reconciled to Truth, those in bondage, those in debt, in the cause of Allah, and for the stranded traveler.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card shadow mb-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0">Zakah Distribution</h5>
                            </div>
                            <div class="card-body">
                                <div class="zakah-distribution-info">
                                    <p>When you donate your Zakah through CharityWorks, we ensure it reaches those most in need according to Islamic principles.</p>
                                    
                                    <h6 class="mt-4">Our Zakah Projects</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i> Emergency Relief</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i> Clean Water Access</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i> Food Security Programs</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i> Medical Aid</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i> Orphan Sponsorship</li>
                                        <li><i class="fas fa-check-circle text-success mr-2"></i> Education Support</li>
                                    </ul>
                                </div>
                                
                                <div class="text-center mt-4">
                                    <a href="make-donation.html" class="btn btn-primary btn-block">Donate Zakah Now</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card shadow">
                            <div class="card-body text-center">
                                <h5 class="mb-3">Need Help?</h5>
                                <p>If you have questions about Zakah calculation or distribution, our team is here to help.</p>
                                <div class="contact-options mt-4">
                                    <a href="tel:+9901010000888" class="btn btn-outline-primary btn-block mb-3">
                                        <i class="fas fa-phone-alt mr-2"></i> Call Us
                                    </a>
                                    <a href="#" class="btn btn-outline-primary btn-block mb-3">
                                        <i class="fas fa-comments mr-2"></i> Live Chat
                                    </a>
                                    <a href="contact.html" class="btn btn-outline-primary btn-block">
                                        <i class="fas fa-envelope mr-2"></i> Email Us
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Zakah Calculator Section End -->
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
                                        <a href="index.html"><img src="assets/img/logo/logo2_footer.png" alt=""></a>
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
                                        <li><a href="#">Proparties</a></li>
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
                                <div class="footer-form" >
                                    <div id="mc_embed_signup">
                                        <form target="_blank" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01"
                                        method="get" class="subscribe_form relative mail_part">
                                            <input type="email" name="email" id="newsletter-form-email" placeholder="Email Address"
                                            class="placeholder hide-on-focus" onfocus="this.placeholder = ''"
                                            onblur="this.placeholder = ' Email Address '">
                                            <div class="form-icon">
                                                <button type="submit" name="submit" id="newsletter-submit"
                                                class="email_icon newsletter-submit button-contactForm"><img src="assets/img/gallery/form.png" alt=""></button>
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
                                <div class="footer-copy-right">
                                    <p>Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved</p>
                                </div>
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
    <div id="back-top" >
        <a title="Go to Top" href="#"> <i class="fas fa-level-up-alt"></i></a>
    </div>

    <!-- JS here -->
    <script src="./assets/js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="./assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="./assets/js/popper.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.slicknav.min.js"></script>
    <script src="./assets/js/owl.carousel.min.js"></script>
    <script src="./assets/js/slick.min.js"></script>
    <script src="./assets/js/wow.min.js"></script>
    <script src="./assets/js/animated.headline.js"></script>
    <script src="./assets/js/jquery.magnific-popup.js"></script>
    <script src="./assets/js/gijgo.min.js"></script>
    <script src="./assets/js/jquery.nice-select.min.js"></script>
    <script src="./assets/js/jquery.sticky.js"></script>
    <script src="./assets/js/jquery.barfiller.js"></script>
    <script src="./assets/js/jquery.counterup.min.js"></script>
    <script src="./assets/js/waypoints.min.js"></script>
    <script src="./assets/js/jquery.countdown.min.js"></script>
    <script src="./assets/js/hover-direction-snake.min.js"></script>
    <script src="./assets/js/contact.js"></script>
    <script src="./assets/js/jquery.form.js"></script>
    <script src="./assets/js/jquery.validate.min.js"></script>
    <script src="./assets/js/mail-script.js"></script>
    <script src="./assets/js/jquery.ajaxchimp.min.js"></script>
    <script src="./assets/js/plugins.js"></script>
    <script src="./assets/js/main.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize nice select for dropdown
            $('select').niceSelect();
            
            // Auto-calculate as user inputs values
            $('#zakahCalculatorForm input[type="number"]').on('input', function() {
                updateTotals();
            });
            
            function updateTotals() {
                // This function could be used for real-time calculation previews
                // Left intentionally minimal as the form submits for full calculations
            }
        });
    </script>
</body>
</html>