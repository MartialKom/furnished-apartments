<?= $this->extend('frontend/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb Area -->
<section class="breadcrumb-area d-flex align-items-center" style="background-image:url(<?= base_url('assets/frontend/images/slider/slider1.png') ?>); background-size: cover; background-position: center;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-12 col-lg-12">
                <div class="breadcrumb-wrap text-center">
                    <div class="breadcrumb-title">
                        <h2><?= lang('Common.about_page_title') ?></h2>
                        <div class="breadcrumb-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>"><?= lang('Common.nav_home') ?></a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?= lang('Common.about_breadcrumb') ?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Area End -->

<!-- About Section -->
<section class="about-section pt-120 pb-90">
    <div class="container">
        <div class="row align-items-center mb-60">
            <div class="col-lg-6 mb-30">
                <div class="about-img">
                    <img src="<?= base_url('assets/frontend/images/features/about.png') ?>" alt="<?= lang('Common.nav_about') ?>" style="border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <div class="experience-badge">
                        <div class="badge-content">
                            <h3>3+</h3>
                            <p><?= lang('Common.about_years_experience') ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-30">
                <div class="about-content">
                    <span class="sub-title" style="color: #d29751; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;"><?= lang('Common.about_who_title') ?></span>
                    <h2 class="title mb-20" style="font-size: 42px; font-weight: 700; line-height: 1.2;"><?= lang('Common.about_welcome_title') ?></h2>
                    <p class="mb-20" style="font-size: 16px; line-height: 1.8; color: #666;">
                        <?= lang('Common.about_intro_text_1') ?>
                    </p>
                    <p class="mb-30" style="font-size: 16px; line-height: 1.8; color: #666;">
                        <?= lang('Common.about_intro_text_2') ?>
                    </p>
                    <div class="row">
                        <div class="col-md-6 mb-20">
                            <div class="feature-item" style="display: flex; align-items: start;">
                                <div class="icon" style="background: rgba(210, 151, 81, 0.1); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                    <i class="fas fa-check" style="color: #d29751; font-size: 20px;"></i>
                                </div>
                                <div>
                                    <h5 style="font-weight: 600; margin-bottom: 5px;"><?= lang('Common.about_premium_location') ?></h5>
                                    <p style="color: #666; font-size: 14px; margin: 0;"><?= lang('Common.about_premium_location_desc') ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-20">
                            <div class="feature-item" style="display: flex; align-items: start;">
                                <div class="icon" style="background: rgba(210, 151, 81, 0.1); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                    <i class="fas fa-check" style="color: #d29751; font-size: 20px;"></i>
                                </div>
                                <div>
                                    <h5 style="font-weight: 600; margin-bottom: 5px;"><?= lang('Common.about_service_24_7') ?></h5>
                                    <p style="color: #666; font-size: 14px; margin: 0;"><?= lang('Common.about_service_24_7_desc') ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="stats-section py-60" style="background: linear-gradient(135deg, #d29751 0%, #b87d3f 100%); border-radius: 15px; margin-bottom: 60px;">
            <div class="row text-center">
                <div class="col-lg-3 col-md-6 mb-30">
                    <div class="stat-item">
                        <h2 style="color: white; font-size: 48px; font-weight: 700; margin-bottom: 10px;">15+</h2>
                        <p style="color: rgba(255,255,255,0.9); font-size: 16px; margin: 0;"><?= lang('Common.stat_apartments') ?></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-30">
                    <div class="stat-item">
                        <h2 style="color: white; font-size: 48px; font-weight: 700; margin-bottom: 10px;">200+</h2>
                        <p style="color: rgba(255,255,255,0.9); font-size: 16px; margin: 0;"><?= lang('Common.stat_clients') ?></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-30">
                    <div class="stat-item">
                        <h2 style="color: white; font-size: 48px; font-weight: 700; margin-bottom: 10px;">3+</h2>
                        <p style="color: rgba(255,255,255,0.9); font-size: 16px; margin: 0;"><?= lang('Common.stat_experience') ?></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-30">
                    <div class="stat-item">
                        <h2 style="color: white; font-size: 48px; font-weight: 700; margin-bottom: 10px;">100%</h2>
                        <p style="color: rgba(255,255,255,0.9); font-size: 16px; margin: 0;"><?= lang('Common.stat_satisfaction') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mission & Vision -->
        <div class="row mb-60">
            <div class="col-lg-6 mb-30">
                <div class="mission-card" style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); height: 100%;">
                    <div class="icon-box mb-20" style="width: 70px; height: 70px; background: rgba(210, 151, 81, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-bullseye" style="font-size: 32px; color: #d29751;"></i>
                    </div>
                    <h3 style="font-size: 28px; font-weight: 700; margin-bottom: 20px;"><?= lang('Common.about_mission_title') ?></h3>
                    <p style="font-size: 16px; line-height: 1.8; color: #666; margin: 0;">
                        <?= lang('Common.about_mission_text') ?>
                    </p>
                </div>
            </div>
            <div class="col-lg-6 mb-30">
                <div class="vision-card" style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); height: 100%;">
                    <div class="icon-box mb-20" style="width: 70px; height: 70px; background: rgba(210, 151, 81, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-eye" style="font-size: 32px; color: #d29751;"></i>
                    </div>
                    <h3 style="font-size: 28px; font-weight: 700; margin-bottom: 20px;"><?= lang('Common.about_vision_title') ?></h3>
                    <p style="font-size: 16px; line-height: 1.8; color: #666; margin: 0;">
                        <?= lang('Common.about_vision_text') ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Our Values -->
        <div class="values-section">
            <div class="text-center mb-50">
                <span class="sub-title" style="color: #d29751; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;"><?= lang('Common.about_values_subtitle') ?></span>
                <h2 class="title" style="font-size: 42px; font-weight: 700;"><?= lang('Common.about_values_title') ?></h2>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-30">
                    <div class="value-card" style="text-align: center; padding: 40px 30px; background: white; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); transition: all 0.3s;">
                        <div class="icon-box mb-20" style="width: 80px; height: 80px; background: rgba(210, 151, 81, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="fas fa-shield-alt" style="font-size: 36px; color: #d29751;"></i>
                        </div>
                        <h4 style="font-size: 22px; font-weight: 600; margin-bottom: 15px;"><?= lang('Common.value_integrity') ?></h4>
                        <p style="color: #666; font-size: 15px; line-height: 1.6;"><?= lang('Common.value_integrity_desc') ?></p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-30">
                    <div class="value-card" style="text-align: center; padding: 40px 30px; background: white; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); transition: all 0.3s;">
                        <div class="icon-box mb-20" style="width: 80px; height: 80px; background: rgba(210, 151, 81, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="fas fa-star" style="font-size: 36px; color: #d29751;"></i>
                        </div>
                        <h4 style="font-size: 22px; font-weight: 600; margin-bottom: 15px;"><?= lang('Common.value_excellence') ?></h4>
                        <p style="color: #666; font-size: 15px; line-height: 1.6;"><?= lang('Common.value_excellence_desc') ?></p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-30">
                    <div class="value-card" style="text-align: center; padding: 40px 30px; background: white; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); transition: all 0.3s;">
                        <div class="icon-box mb-20" style="width: 80px; height: 80px; background: rgba(210, 151, 81, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="fas fa-heart" style="font-size: 36px; color: #d29751;"></i>
                        </div>
                        <h4 style="font-size: 22px; font-weight: 600; margin-bottom: 15px;"><?= lang('Common.value_kindness') ?></h4>
                        <p style="color: #666; font-size: 15px; line-height: 1.6;"><?= lang('Common.value_kindness_desc') ?></p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-30">
                    <div class="value-card" style="text-align: center; padding: 40px 30px; background: white; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); transition: all 0.3s;">
                        <div class="icon-box mb-20" style="width: 80px; height: 80px; background: rgba(210, 151, 81, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="fas fa-lightbulb" style="font-size: 36px; color: #d29751;"></i>
                        </div>
                        <h4 style="font-size: 22px; font-weight: 600; margin-bottom: 15px;"><?= lang('Common.value_innovation') ?></h4>
                        <p style="color: #666; font-size: 15px; line-height: 1.6;"><?= lang('Common.value_innovation_desc') ?></p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-30">
                    <div class="value-card" style="text-align: center; padding: 40px 30px; background: white; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); transition: all 0.3s;">
                        <div class="icon-box mb-20" style="width: 80px; height: 80px; background: rgba(210, 151, 81, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="fas fa-handshake" style="font-size: 36px; color: #d29751;"></i>
                        </div>
                        <h4 style="font-size: 22px; font-weight: 600; margin-bottom: 15px;"><?= lang('Common.value_commitment') ?></h4>
                        <p style="color: #666; font-size: 15px; line-height: 1.6;"><?= lang('Common.value_commitment_desc') ?></p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-30">
                    <div class="value-card" style="text-align: center; padding: 40px 30px; background: white; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); transition: all 0.3s;">
                        <div class="icon-box mb-20" style="width: 80px; height: 80px; background: rgba(210, 151, 81, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="fas fa-users" style="font-size: 36px; color: #d29751;"></i>
                        </div>
                        <h4 style="font-size: 22px; font-weight: 600; margin-bottom: 15px;"><?= lang('Common.value_community') ?></h4>
                        <p style="color: #666; font-size: 15px; line-height: 1.6;"><?= lang('Common.value_community_desc') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-90" style="background: linear-gradient(135deg, #d29751 0%, #b87d3f 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 style="color: white; font-size: 36px; font-weight: 700; margin-bottom: 15px;"><?= lang('Common.about_cta_title') ?></h2>
                <p style="color: rgba(255,255,255,0.9); font-size: 18px; margin: 0;"><?= lang('Common.about_cta_subtitle') ?></p>
            </div>
            <div class="col-lg-4 text-lg-end mt-lg-0 mt-4">
                <a href="<?= base_url('apartments') ?>" class="btn btn-white" style="background: white; color: #d29751; padding: 15px 40px; border-radius: 50px; font-weight: 600; font-size: 16px; display: inline-block; text-decoration: none;">
                    <i class="fas fa-home me-2"></i><?= lang('Common.about_cta_button') ?>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
/* Breadcrumb Area */
.breadcrumb-area {
    padding: 120px 0 80px;
    position: relative;
}

.breadcrumb-area::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
}

.breadcrumb-wrap {
    position: relative;
    z-index: 1;
}

.breadcrumb-title h2 {
    color: white;
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 20px;
}

.breadcrumb-wrap .breadcrumb {
    background: transparent;
    justify-content: center;
    margin-bottom: 0;
}

.breadcrumb-item a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
}

.breadcrumb-item.active {
    color: white;
}

.breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255, 255, 255, 0.6);
}

/* Experience Badge */
.about-img {
    position: relative;
}

.experience-badge {
    position: absolute;
    bottom: 30px;
    right: 30px;
    background: #d29751;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.badge-content {
    text-align: center;
    color: white;
}

.badge-content h3 {
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 5px;
}

.badge-content p {
    margin: 0;
    font-size: 14px;
}

/* Value Cards Hover Effect */
.value-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15) !important;
}

/* CTA Button Hover */
.btn-white:hover {
    background: rgba(255,255,255,0.9) !important;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}

/* Responsive */
@media (max-width: 768px) {
    .breadcrumb-title h2 {
        font-size: 32px;
    }

    .title {
        font-size: 28px !important;
    }

    .stat-item h2 {
        font-size: 36px !important;
    }
}
</style>

<?= $this->endSection() ?>
