<?= $this->extend('frontend/layouts/main') ?>

<?= $this->section('content') ?>

    <!-- slider-area -->
    <section id="home" class="slider-area fix p-relative">
        <div class="slider-active">
            <div class="single-slider slider-bg d-flex align-items-center"
                 style="background-image:url(<?= base_url('assets/frontend/images/slider/slider1.png') ?>)">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="slider-content s-slider-content text-left">
                                <h2 data-animation="fadeInUp" data-delay=".4s"><?= lang('Common.hero_title') ?></h2>
                                <ul data-animation="fadeInUp" data-delay=".6s">
                                    <li>
                                        <i class="fas fa-bed"></i>
                                        <span>3 <?= lang('Common.bedrooms') ?></span>
                                    </li>
                                    <li>
                                        <i class="fas fa-bath"></i>
                                        <span>2 <?= lang('Common.bathrooms') ?></span>
                                    </li>
                                    <li>
                                        <i class="fas fa-car"></i>
                                        <span><?= lang('Common.parking_access') ?></span>
                                    </li>
                                </ul>
                                <div class="slider-btn mt-55">
                                    <a href="<?= base_url('reservation') ?>" class="btn ss-btn" data-animation="fadeInRight" data-delay=".8s"><?= lang('Common.book_now') ?></a>
                                    <a href="tel:+237671387969" class="btn ss-btn ms-3" style="background: #25D366;" data-animation="fadeInRight" data-delay="1s">
                                        <i class="fas fa-phone me-2"></i>+237 6 71 38 79 69
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="slider-price">
                                <h3><?= lang('Common.pricing_label') ?>:</h3>
                                <h2><?= lang('Common.from') ?> 40 000 Fcfa<?= lang('Common.per_day_short') ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="single-slider slider-bg d-flex align-items-center"
                 style="background-image:url(<?= base_url('assets/frontend/images/slider/slider2.png') ?>)">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="slider-content s-slider-content text-left">
                                <h2 data-animation="fadeInUp" data-delay=".4s"><?= lang('Common.hero_subtitle') ?></h2>
                                <ul data-animation="fadeInUp" data-delay=".6s">
                                    <li>
                                        <i class="fas fa-bed"></i>
                                        <span>3 <?= lang('Common.bedrooms') ?></span>
                                    </li>
                                    <li>
                                        <i class="fas fa-bath"></i>
                                        <span>2 <?= lang('Common.bathrooms') ?></span>
                                    </li>
                                    <li>
                                        <i class="fas fa-car"></i>
                                        <span><?= lang('Common.parking_access') ?></span>
                                    </li>
                                </ul>
                                <div class="slider-btn mt-55">
                                    <a href="<?= base_url('reservation') ?>" class="btn ss-btn" data-animation="fadeInRight" data-delay=".8s"><?= lang('Common.book_now') ?></a>
                                    <a href="tel:+237671387969" class="btn ss-btn ms-3" style="background: #25D366;" data-animation="fadeInRight" data-delay="1s">
                                        <i class="fas fa-phone me-2"></i>+237 6 71 38 79 69
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="slider-price">
                                <h3><?= lang('Common.pricing_label') ?>:</h3>
                                <h2><?= lang('Common.from') ?> 40 000 Fcfa<?= lang('Common.per_day_short') ?></h2>
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
                        <img src="<?= base_url('assets/frontend/images/features/about.png') ?>" alt="img">
                        <div class="about-text second-about">
                            <span>3 <?= lang('Common.years_experience') ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content s-about-content pl-30">
                        <div class="about-title second-atitle">
                            <span><?= lang('Common.about_us_span') ?></span>
                            <h2><?= lang('Common.welcome_building') ?></h2>
                            <p><span></span><?= lang('Common.services_subtitle_desc') ?></p>
                        </div>
                        <p><?= lang('Common.about_description') ?></p>
                        <a href="<?= base_url('reservation') ?>" class="btn"><?= lang('Common.book_now_alt') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- about-area-end -->

    <!-- counter-area -->
    <div class="counter-area pt-120 pb-90"
         style="background-image:url(<?= base_url('assets/frontend/images/logo/count-bg.jpg') ?>); background-size:cover;">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="single-counter text-center mb-30 wow fadeInUp animated">
                        <i class="fa fa-plus-square-o"></i>
                        <div class="counter p-relative">
                            <span class="count">30</span>
                        </div>
                        <p><?= lang('Common.rooms_counter') ?></p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single-counter text-center mb-30 wow fadeInUp animated">
                        <i class="fas fa-bath"></i>
                        <div class="counter p-relative">
                            <span class="count">10</span>
                        </div>
                        <p><?= lang('Common.bathrooms_counter') ?></p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single-counter text-center mb-30 wow fadeInUp animated">
                        <i class="fas fa-bed"></i>
                        <div class="counter p-relative">
                            <span class="count">25</span>
                        </div>
                        <p><?= lang('Common.bedrooms_counter') ?></p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single-counter text-center mb-30 wow fadeInUp animated">
                        <i class="fas fa-car"></i>
                        <div class="counter p-relative">
                            <span class="count">15</span>
                        </div>
                        <p><?= lang('Common.parking_spaces') ?></p>
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
                        <span><?= lang('Common.our_gems') ?></span>
                        <h2><?= lang('Common.interior_view') ?></h2>
                    </div>
                </div>
            </div>
            <div class="row services-active">
                <div class="col-xl-4">
                    <div class="single-services mb-30">
                        <div class="services-thumb">
                            <a class="gallery-link popup-image"
                               href="<?= base_url('assets/frontend/images/gallery/interior1.png') ?>">
                                <img src="<?= base_url('assets/frontend/images/gallery/interior1.png') ?>" alt="img">
                            </a>
                        </div>
                        <div class="services-content">
                            <small><?= lang('Common.explore_now') ?></small>
                            <h4><a href="apartments-details.html"><?= lang('Common.apartment_4_rooms') ?></a></h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="single-services mb-30">
                        <div class="services-thumb">
                            <a class="gallery-link popup-image"
                               href="<?= base_url('assets/frontend/images/gallery/interior2.png') ?>">
                                <img src="<?= base_url('assets/frontend/images/gallery/interior2.png') ?>" alt="img">
                            </a>
                        </div>
                        <div class="services-content">
                            <small><?= lang('Common.explore_now') ?></small>
                            <h4><a href="apartments-details.html"><?= lang('Common.luxury_apartment') ?></a></h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="single-services mb-30">
                        <div class="services-thumb">
                            <a class="gallery-link popup-image"
                               href="<?= base_url('assets/frontend/images/gallery/interior3.png') ?>">
                                <img src="<?= base_url('assets/frontend/images/gallery/interior3.png') ?>" alt="img">
                            </a>
                        </div>
                        <div class="services-content">
                            <small><?= lang('Common.explore_now_fr') ?></small>
                            <h4><a href="apartments-details.html"><?= lang('Common.deluxe_apartment') ?></a></h4>
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
                        <img src="<?= base_url('assets/frontend/images/features/about3.png') ?>" alt="img">
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="choose-wrap">
                        <div class="section-title w-title left-align mb-35 wow fadeInDown animated">
                            <span><?= lang('Common.best_plan') ?></span>
                            <h2><?= lang('Common.for_rent') ?></h2>
                        </div>
                        <div class="choose-content wow fadeInUp animated">
                            <p><?= lang('Common.comfort_description') ?></p>

                            <div class="choose-list mt-20 mb-20">
                                <ul>
                                    <li>
                                        <i class="fas fa-bed"></i>
                                        <span>3 <?= lang('Common.bedrooms') ?></span>
                                    </li>
                                    <li>
                                        <i class="fa fa-plus-square-o"></i>
                                        <span><?= lang('Common.rooms_4') ?></span>
                                    </li>
                                    <li>
                                        <i class="fas fa-bath"></i>
                                        <span><?= lang('Common.bathrooms_2') ?></span>
                                    </li>
                                    <li>
                                        <i class="fas fa-car"></i>
                                        <span><?= lang('Common.parking_access') ?></span>
                                    </li>
                                </ul>
                            </div>
                            <h3><?= lang('Common.pricing_label') ?>:</h3>
                            <h2><?= lang('Common.from') ?> 40 000 FCFA<?= lang('Common.per_day_short') ?></h2>
                            <div class="choose-btn mt-30">
                                <a href="#" class="btn"><?= lang('Common.nav_contact') ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- choose-area-end -->

    <!-- testimonial-area
    <section id="testimonios" class="testimonial-area gray-bg testimonial-p pt-115 pb-185 text-center"
             style="background-image:url(<?= base_url('assets/frontend/images/testimonial/test-bg.jpg') ?>)">
        <div class="container">
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <div class="section-title center-align mb-40 wow fadeInDown animated">
                        <span>Expériences client</span>
                        <h2>Témoignages</h2>
                    </div>
                    <div class="testimonial-active">
                        <div class="single-testimonial">
                            <i class="fas fa-quote-left"></i>
                            <p>"Depuis que j'ai emménagé dans cet immeuble, ma vie a complètement changé.
                                Les appartements sont non seulement élégamment meublés, mais ils offrent également un confort inégalé.
                                Chaque détail a été pensé pour créer un espace accueillant et fonctionnel."</p>
                            <div class="testi-author text-center">
                                <img src="<?= base_url('assets/frontend/images/testimonial/testi_avatar.png') ?>"
                                     alt="img">
                                <div class="ta-info">
                                    <h6>Mr KABONG Jean</h6>
                                    <span>Client satisfait</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2"></div>
            </
    </section>
    testimonial-area-end -->

    <!-- apartments-area -->
    <section class="apartments pt-120 pb-90">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="section-title text-center pl-40 pr-40 mb-80 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
                    <span><?= lang('Common.our_plans') ?></span>
                    <h2><?= lang('Common.apartments_and_studios') ?></h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
            </div>
            <div class="col-lg-6">
                <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link nav-link active" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true"><?= lang('Common.studio') ?></a>
                        <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false"><?= lang('Common.apartments') ?></a>
                    </div>
                </nav>
            </div>
            <div class="col-lg-3">
            </div>
            <div class="col-lg-12 ">
                <div class="tab-content py-3 px-3 px-sm-0 mt-50" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <div class="row">
                            <div class="col-lg-6">
                                <p><?= lang('Common.studio_description_1') ?></p>
                                <p><?= lang('Common.studio_description_2') ?></p>
                                <ul>
                                    <li><?= lang('Common.floor_number') ?><span class="after"> 3</span></li>
                                    <li><?= lang('Common.room_number') ?><span class="after"> 2</span></li>
                                    <li><?= lang('Common.price_label') ?><span class="after"> 20000<?= lang('Common.price_month') ?></span></li>
                                </ul>
                                <div class="mt-30">
                                    <a href="<?= base_url('reservation') ?>" class="btn" style="background: #d29751; color: white; padding: 12px 25px; border-radius: 5px; text-decoration: none; display: inline-block; transition: all 0.3s ease;">
                                        <i class="fas fa-calendar-check" style="margin-right: 8px;"></i>
                                        <?= lang('Common.book_this_studio') ?>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="apartments-img">
                                    <img src="<?= base_url('template/img/studio.png') ?>" alt="floor-chart"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                        <div class="row">

                            <div class="col-lg-6">
                                <p><?= lang('Common.apartment_description_1') ?></p>
                                <p><?= lang('Common.apartment_description_2') ?></p>
                                <ul>
                                    <li><?= lang('Common.floor_number') ?><span class="after"> 2</span></li>
                                    <li><?= lang('Common.room_number') ?><span class="after"> 4</span></li>
                                    <li><?= lang('Common.price_label') ?><span class="after"> 25000<?= lang('Common.price_month') ?></span></li>
                                </ul>
                                <div class="mt-30">
                                    <a href="<?= base_url('reservation') ?>" class="btn" style="background: #d29751; color: white; padding: 12px 25px; border-radius: 5px; text-decoration: none; display: inline-block; transition: all 0.3s ease;">
                                        <i class="fas fa-calendar-check" style="margin-right: 8px;"></i>
                                        <?= lang('Common.book_this_apartment') ?>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="apartments-img">
                                    <img src="<?= base_url('template/img/apart.png')  ?>" alt="floor-chart"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>


    </div>
</section>
    <!-- apartments-area-end -->


    <!-- contact-area -->
    <section id="contact" class="contact-area contact-bg pt-120 pb-120 p-relative fix" style="background-image:url(<?= base_url('assets/frontend/images/bg/contact-us.png') ?>)">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8">
                <div class="section-title text-center mb-80 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
                    <span><?= lang('Common.nav_contact') ?></span>
                    <h2><?= lang('Common.stay_in_touch') ?></h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="contact-info">
                    <div class="single-cta pb-30 mb-30 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                        <div class="f-cta-icon">
                            <i class="far fa-map"></i>
                        </div>
                        <h5><?= lang('Common.address_label') ?></h5>
                        <p><?= lang('Common.address_text') ?> <br></p>
                    </div>
                    <div class="single-cta pb-30 mb-30 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                        <div class="f-cta-icon">
                            <i class="far fa-clock"></i>
                        </div>
                        <h5><?= lang('Common.availability_hours') ?></h5>
                        <p><?= lang('Common.availability_text') ?> <br></p>
                    </div>
                    <div class="single-cta pb-30 mb-30 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                        <div class="f-cta-icon">
                            <i class="far fa-envelope-open"></i>
                        </div>
                        <h5><?= lang('Common.send_message_label') ?></h5>
                        <p><?= lang('Common.send_message_text') ?> <a href="#">support&#64;nsenou-tower.com</a></p>
                    </div>
                </div>

            </div>
            <div class="col-lg-8">
                <form action="#" class="contact-form wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="contact-field p-relative c-name mb-40">
                                <input type="text" placeholder="<?= lang('Common.form_name_placeholder') ?>">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="contact-field p-relative c-email mb-40">
                                <input type="text" placeholder="<?= lang('Common.form_email_placeholder') ?>">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="contact-field p-relative c-subject mb-40">
                                <input type="text" placeholder="<?= lang('Common.form_subject_placeholder') ?>">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="contact-field p-relative c-message mb-45">
                                <textarea name="message" id="message" cols="30" rows="10" placeholder="<?= lang('Common.form_message_placeholder') ?>"></textarea>
                            </div>
                            <button class="btn"><?= lang('Common.send_button') ?></button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

    </div>

</section>
    <!-- contact-area-end -->

<style>
/* Styles pour les boutons de réservation */
.btn:hover {
    background: #b8834a !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(210, 151, 81, 0.3) !important;
}

/* Animation pour les nouveaux boutons de réservation */
.mt-30 .btn {
    transition: all 0.3s ease;
}

.mt-30 .btn:hover {
    background: #b8834a !important;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(210, 151, 81, 0.4) !important;
}
</style>

<?= $this->endSection() ?>