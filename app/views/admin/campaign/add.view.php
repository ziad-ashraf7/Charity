<?php loadPartial(\Core\System::HEADER); ?>
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
<?php loadPartial(\Core\System::NAVBAR); ?>
<main>
    <!--? Hero Start -->
    <div class="slider-area2">
        <div class="slider-height2 d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="hero-cap hero-cap2 text-center">
                            <h2>Add New Campaign</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Login Form Start -->
    <section class="contact-section section-padding30">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <?php loadPartial(\Core\System::ERRORS); ?>
                    <form class="form-contact contact_form" enctype="multipart/form-data" action="/admin/campaign/add" method="post" id="loginForm"
                          novalidate="novalidate">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control" name="name" id="name" type="text"
                                           onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Campaign Name Here'"
                                           placeholder="Enter Campaign Name Here">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control" name="img" id="img" type="file">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-30 text-center">
                            <button type="submit" class="button button-contactForm boxed-btn">Add</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Login Form End -->
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