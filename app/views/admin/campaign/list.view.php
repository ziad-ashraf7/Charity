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
<!-- header end -->
<main>
    <!--? Hero Start -->
    <div class="slider-area2">
        <div class="slider-height2 d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="hero-cap hero-cap2 pt-20 text-center">
                            <h2>Available Campaigns</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->
    <!--? Count Down Start -->
    <div class="count-down-area pt-25 section-bg" data-background="assets/img/gallery/section_bg02.png">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12">
                    <div class="count-down-wrapper">
                        <div class="row justify-content-between">
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <!-- Counter Up -->
                                <div class="single-counter text-center">
                                    <span class="counter color-green">6,200</span>
                                    <span class="plus">+</span>
                                    <p class="color-green">Donation</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <!-- Counter Up -->
                                <div class="single-counter text-center">
                                    <span class="counter color-green">80</span>
                                    <span class="plus">+</span>
                                    <p class="color-green">Fund Raised</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <!-- Counter Up -->
                                <div class="single-counter text-center">
                                    <span class="counter color-green">256</span>
                                    <span class="plus">+</span>
                                    <p class="color-green">Donation</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <!-- Counter Up -->
                                <div class="single-counter text-center">
                                    <span class="counter color-green">256</span>
                                    <span class="plus">+</span>
                                    <p class="color-green">Donation</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Count Down End -->
    <!-- Featured_job_start -->
    <section class="featured-job-area section-padding30 section-bg2"
             data-background="assets/img/gallery/section_bg03.png">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-9 col-md-10 col-sm-12">
                    <!-- Section Tittle -->
                    <div class="section-tittle text-center mb-80">
                        <span>What we are boing</span>
                        <h2>We arrange many social events for charity donations</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <?php foreach ($campaigns as $campaign) : ?>
                    <div class="col-lg-9 col-md-12">
                        <!-- single-job-content -->
                        <div class="single-job-items mb-30">
                            <div class="job-items">
                                <div class="company-img">
                                    <a href="<?php echo \App\controllers\CampaignController::BASE_ENDPOINT . '/add?campaignId=' . $campaign->id ?>"><img
                                                width="50%"
                                                src="<?php echo getImage(\App\controllers\CampaignController::IMG_PATH, $campaign->img); ?>"
                                                alt=""></a>
                                </div>
                                <div class="job-tittle">
                                    <a href="#"><h4><?php echo $campaign->name ?></h4></a>
                                    <a href="<?php echo \App\controllers\CampaignController::BASE_ENDPOINT . '/update/'. $campaign->id ?>">
                                        <h4>Update</h4>
                                    </a>

                                    <!--                                    <ul>-->
                                    <!--                                        <li><i class="far fa-clock"></i>8:30 - 9:30am</li>-->
                                    <!--                                        <li><i class="fas fa-sort-amount-down"></i>18.01.2021</li>-->
                                    <!--                                        <li><i class="fas fa-map-marker-alt"></i>Athens, Greece</li>-->
                                    <!--                                    </ul>-->
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- Featured_job_end -->
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