<?php loadPartial('header'); ?>
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
    <?php loadPartial('navBar'); ?>
    <main>
        <!--? Hero Start -->
        <div class="slider-area2">
            <div class="slider-height2 d-flex align-items-center">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="hero-cap hero-cap2 text-center">
                                <h2>Login</h2>
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
                        <div class="section-tittle text-center mb-30">
                            <span>Login to your account</span>
                            <h2>Welcome Back!</h2>
                        </div>
                        <?php loadPartial('errors'); ?>
                        <form class="form-contact contact_form" action="/user/login" method="post" id="loginForm" novalidate="novalidate">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input class="form-control" name="email" id="email" type="email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter email'" placeholder="Enter email">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <input class="form-control" name="password" id="password" type="password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter password'" placeholder="Enter password">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                </div>
                            </div>
                            <div class="form-group mt-30 text-center">
                                <button type="submit" class="button button-contactForm boxed-btn">Login</button>
                            </div>
                            <div class="form-group text-center mt-20">
                                <p>Don't have an account? <a href="/register.html" class="text-primary">Register now</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- Login Form End -->
    </main>

    <?php loadPartial('footer'); ?>

    <!-- Scroll Up -->
    <div id="back-top" >
        <a title="Go to Top" href="#"> <i class="fas fa-level-up-alt"></i></a>
    </div>

    <!-- JS here -->
    <?php loadPartial('js'); ?>
</body>
</html>