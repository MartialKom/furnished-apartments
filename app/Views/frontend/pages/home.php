<?= $this->extend('frontend/layouts/main') ?>

<?= $this->section('content') ?>

<!-- slider-area -->
<section id="home" class="slider-area fix p-relative">
    <div class="slider-active">
        <div class="single-slider slider-bg d-flex align-items-center" style="background-image:url(<?= base_url('assets/frontend/images/slider/slider_img01.jpg') ?>)">
            <div class="container">
                <div class="row">							
                    <div class="col-lg-8">
                        <div class="slider-content s-slider-content text-left">
                            <h2 data-animation="fadeInUp" data-delay=".4s">Discover Modern<br>Building Design.</h2>
                            <ul data-animation="fadeInUp" data-delay=".6s">
                                <li>
                                    <i class="fas fa-bed"></i>
                                    <span>3 Bedrooms.</span>
                                </li>
                                <li>
                                    <i class="fal fa-pencil-ruler"></i>
                                    <span>Square Feet </span>
                                </li>
                                <li>
                                    <i class="fas fa-bath"></i>
                                    <span>Bedrooms</span>
                                </li>
                                <li>
                                    <i class="fas fa-car"></i>
                                    <span>Car parking</span>
                                </li>
                            </ul>
                            <div class="slider-btn mt-55">                                          
                                <a href="#" class="btn ss-btn" data-animation="fadeInRight" data-delay=".8s">Get a Quote</a>
                                <a href="https://www.youtube.com/watch?v=vKSA_idPZkc" class="video-i popup-video" data-animation="fadeInUp" data-delay=".8s" style="animation-delay: 0.8s;" tabindex="0"><i class="fas fa-play"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="slider-price">
                            <h3>Price:</h3>
                            <h2>$1,786.80</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="single-slider slider-bg d-flex align-items-center" style="background-image:url(<?= base_url('assets/frontend/images/slider/slider_img02.jpg') ?>)">
            <div class="container">
                <div class="row">							
                    <div class="col-lg-8">
                        <div class="slider-content s-slider-content text-left">
                            <h2 data-animation="fadeInUp" data-delay=".4s">Discover Modern<br>Building Design.</h2>
                            <ul data-animation="fadeInUp" data-delay=".6s">
                                <li>
                                    <i class="fas fa-bed"></i>
                                    <span>3 Bedrooms.</span>
                                </li>
                                <li>
                                    <i class="fal fa-pencil-ruler"></i>
                                    <span>Square Feet </span>
                                </li>
                                <li>
                                    <i class="fas fa-bath"></i>
                                    <span>Bedrooms</span>
                                </li>
                                <li>
                                    <i class="fas fa-car"></i>
                                    <span>Car parking</span>
                                </li>
                            </ul>
                            <div class="slider-btn mt-55">                                          
                                <a href="#" class="btn ss-btn" data-animation="fadeInRight" data-delay=".8s">Get a Quote</a>
                                <a href="https://www.youtube.com/watch?v=vKSA_idPZkc" class="video-i popup-video" data-animation="fadeInUp" data-delay=".8s" style="animation-delay: 0.8s;" tabindex="0"><i class="fas fa-play"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="slider-price">
                            <h3>Price:</h3>
                            <h2>$2,786.80</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- slider-area-end -->

<!-- about-area -->
<section id="about" class="about-area about-p pt-120 pb-120 p-relative">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="s-about-img p-relative">
                    <img src="<?= base_url('assets/frontend/images/features/about_img02.png') ?>" alt="img">
                    <div class="about-text second-about">
                        <span>35 years of <br> experience</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-content s-about-content pl-30">
                    <div class="about-title second-atitle">
                        <span>About Us</span>
                        <h2>Welcome To Our Furnished Apartments</h2>
                        <p><span></span>We provide an essential service for you</p>
                    </div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    <a href="#" class="btn">Get Started</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- about-area-end -->

<!-- counter-area -->
<div class="counter-area pt-120 pb-90" style="background-image:url(<?= base_url('assets/frontend/images/logo/count-bg.jpg') ?>); background-size:cover;">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="single-counter text-center mb-30 wow fadeInUp animated">
                    <i class="fal fa-pencil-ruler"></i>
                    <div class="counter p-relative">
                        <span class="count">2543</span>                                   
                    </div>
                    <p>Square Feet</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="single-counter text-center mb-30 wow fadeInUp animated">
                    <i class="fas fa-bath"></i>
                    <div class="counter p-relative">
                        <span class="count">4</span>                                   
                    </div>
                    <p>Bathrooms</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="single-counter text-center mb-30 wow fadeInUp animated">
                    <i class="fas fa-bed"></i>
                    <div class="counter p-relative">
                        <span class="count">6</span>                                   
                    </div>
                    <p>Bedrooms</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="single-counter text-center mb-30 wow fadeInUp animated">
                    <i class="fas fa-car"></i>
                    <div class="counter p-relative">
                        <span class="count">4</span>                                   
                    </div>
                    <p>Car parking</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- counter-area-end -->

<!-- gallery-area -->
<section id="services" class="services-area pt-113 pb-150">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="section-title text-center pl-40 pr-40 mb-80">
                    <span>Best Work</span>
                    <h2>Interior Views</h2>
                </div>
            </div>
        </div>
        <div class="row services-active">
            <div class="col-xl-4">
                <div class="single-services mb-30">
                    <div class="services-thumb">
                        <a class="gallery-link popup-image" href="<?= base_url('assets/frontend/images/gallery/gallery.jpg') ?>">
                            <img src="<?= base_url('assets/frontend/images/gallery/gallery.jpg') ?>" alt="img">
                        </a>
                    </div>
                    <div class="services-content">                                   
                        <small>Explore Now</small>
                        <h4><a href="apartments-details.html">One Room Apartment</a></h4>                                   
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="single-services mb-30">
                    <div class="services-thumb">
                        <a class="gallery-link popup-image" href="<?= base_url('assets/frontend/images/gallery/gallery02.jpg') ?>">
                            <img src="<?= base_url('assets/frontend/images/gallery/gallery02.jpg') ?>" alt="img">
                        </a>
                    </div>
                    <div class="services-content">                                   
                         <small>Explore Now</small>
                        <h4><a href="apartments-details.html">Luxury Apartment</a></h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="single-services mb-30">
                    <div class="services-thumb">
                        <a class="gallery-link popup-image" href="<?= base_url('assets/frontend/images/gallery/gallery03.jpg') ?>">
                            <img src="<?= base_url('assets/frontend/images/gallery/gallery03.jpg') ?>" alt="img">
                        </a>
                    </div>
                    <div class="services-content">                                   
                        <small>Explore Now</small>
                        <h4><a href="apartments-details.html">Deluxe Apartment</a></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- gallery-area-end -->

<!-- choose-area -->
<section class="choose-area pt-120 pb-120 p-relative" style="background:#f5f5f5;">             
    <div class="chosse-img wow fadeInRight animated"></div>
    <div class="container">
        <div class="row">
            <div class="col-xl-6">
                <div class="s-about-img p-relative">
                    <img src="<?= base_url('assets/frontend/images/features/about_img03.png') ?>" alt="img">
                </div>
            </div>
            <div class="col-xl-6">
                <div class="choose-wrap">
                    <div class="section-title w-title left-align mb-35 wow fadeInDown animated">
                        <span>Best Place</span>
                        <h2>For Sell Properties</h2>
                    </div>
                    <div class="choose-content wow fadeInUp animated">
                        <p>Pellentesque habitant morbi tristique senectus et netus et fames acturpis egestas. Vestibulum tortor quam, feugiat vitae, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper.</p>
                        
                        <div class="choose-list mt-20 mb-20">
                            <ul>
                                <li>
                                    <i class="fas fa-bed"></i>
                                    <span>3 Bedrooms.</span>
                                </li>
                                <li>
                                    <i class="fal fa-pencil-ruler"></i>
                                    <span>Square Feet </span>
                                </li>
                                <li>
                                    <i class="fas fa-bath"></i>
                                    <span>Bedrooms</span>
                                </li>
                                <li>
                                    <i class="fas fa-car"></i>
                                    <span>Car parking</span>
                                </li>
                            </ul>
                        </div>									
                        <h3>Price:</h3>
                        <h2>$1,786.80</h2>
                        <div class="choose-btn mt-30">
                            <a href="#" class="btn">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- choose-area-end -->

<!-- testimonial-area -->
<section id="testimonios" class="testimonial-area gray-bg testimonial-p pt-115 pb-185 text-center" style="background-image:url(<?= base_url('assets/frontend/images/testimonial/test-bg.jpg') ?>)">
    <div class="container">
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
                <div class="section-title center-align mb-40 wow fadeInDown animated">
                    <span>Experience With me</span>
                    <h2>Testimonials</h2>
                </div>
                <div class="testimonial-active">
                    <div class="single-testimonial">
                        <i class="fas fa-quote-left"></i>
                        <p>"Nam liber tempor cum soluta nobis eleifend option congue is nihil imper per tem por legere me doming vulputate velit esse molestiesoluta nobis eleifend option."</p>
                        <div class="testi-author text-center">
                            <img src="<?= base_url('assets/frontend/images/testimonial/testi_avatar.png') ?>" alt="img">
                            <div class="ta-info">
                                <h6>Mr John Doe</h6>
                                <span>Satisfied Customer</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2"></div>
        </div>
    </div>
</section>
<!-- testimonial-area-end -->

<!-- blog-area -->
<section id="blog" class="blog-area p-relative pt-120 pb-90">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="section-title text-center mb-80 wow fadeInDown animated">
                    <span>New Every Day</span>
                    <h2>Latest News</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="single-post mb-30 wow fadeInUp animated">
                    <div class="blog-thumb">
                        <a href="blog-details.html"><img src="<?= base_url('assets/frontend/images/blog/blog_img01.jpg') ?>" alt="img"></a>
                    </div>
                    <div class="blog-content">
                        <div class="b-meta mb-20">
                            <ul>
                                <li><a href="#">By admin .</a></li>
                                <li><a href="#">5 Dec 2019 .</a></li>
                                <li><a href="#" class="corpo">Real estate </a></li>
                            </ul>
                        </div>
                        <h4><a href="blog-details.html">Making Distributed Product Team Work More With Monday</a></h4>                                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- blog-area-end -->

<!-- contact-area -->
<section id="contact" class="contact-area contact-bg pt-120 pb-120 p-relative fix" style="background-image:url(<?= base_url('assets/frontend/images/bg/contact_bg.jpg') ?>)">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8">
                <div class="section-title text-center mb-80 wow fadeInDown animated">
                    <span>Contact</span>
                    <h2>Get In Touch</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="contact-info">
                    <div class="single-cta pb-30 mb-30 wow fadeInUp animated">
                        <div class="f-cta-icon">
                            <i class="far fa-map"></i>
                        </div>
                        <h5>Office Address</h5>
                        <p>380 St Kilda Road, Melbourne <br>VIC 3004, Australia</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- contact-area-end -->

<!-- brand-area -->
<div class="brand-area pt-60 pb-60" style="background-color:#d29751">
    <div class="container">
        <div class="row brand-active">
            <div class="col-xl-2">
                <div class="single-brand">
                    <img src="<?= base_url('assets/frontend/images/brand/c-logo1.png') ?>" alt="img">
                </div>
            </div>
            <div class="col-xl-2">
                <div class="single-brand">
                    <img src="<?= base_url('assets/frontend/images/brand/c-logo2.png') ?>" alt="img">
                </div>
            </div>
            <div class="col-xl-2">
                <div class="single-brand">
                    <img src="<?= base_url('assets/frontend/images/brand/c-logo3.png') ?>" alt="img">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- brand-area-end -->

<?= $this->endSection() ?>