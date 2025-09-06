<header class="header-area">  
			<div class="header-top second-header d-none d-md-block">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="wellcome-text text-center text-lg-left">
                                <p><?= lang('Common.welcome_message') ?></p>
                            </div>
                        </div>
                        <div class="col-lg-6 d-none d-lg-block">
                            <div class="header-cta text-right">
                                <ul>
                                    <li>
                                        <i class="icon dripicons-phone"></i>
                                        <span>+1 (438) 467-2660</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-mail"></i>
                                        <span>info&#64;nsenou-tower.com</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-clock"></i>
                                        <span>24/7</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>		
            <div id="header-sticky" class="menu-area">
                <div class="container">
                    <div class="second-menu">
                        <div class="row align-items-center">
                            <div class="col-xl-2 col-lg-2">
                                <div class="logo">
                                    <a href="index.html"><img src="<?= base_url('assets/frontend/images/logo/nsenou.png') ?>" alt="logo"></a>
                                </div>
                            </div>
                            <div class="col-xl-10 col-lg-9">
                                <div class="responsive"><i class="icon dripicons-align-right"></i></div>
                                <div class="main-menu text-right <text-xl-right></text-xl-right>">
                                    <nav id="mobile-menu">
                                        <ul>
                                            <li class="has-sub"><a href="<?= base_url('/') ?>" ><?= lang('Common.nav_home') ?></a></li>
                                            <li><a href="about.html"><?= lang('Common.nav_about') ?></a></li>
                                            <li><a href="services.html"><?= lang('Common.nav_services') ?></a></li>
                                            <li><a href="apartments.html"><?= lang('Common.nav_apartments') ?></a></li>
                                            <li><a href="contact.html"><?= lang('Common.nav_contact') ?></a></li>
                                            <li class="has-sub">
                                                <a href="javascript:void(0)" class="top-btn" style="display: flex; align-items: center;">
                                                    <?php $currentLang = session()->get('language') ?? 'fr'; ?>
                                                    <?php if($currentLang == 'fr'): ?>
                                                        <img src="<?= base_url('assets/frontend/images/flags/fr.svg') ?>" alt="Français" style="width: 20px; height: 15px; margin-right: 5px;">
                                                        <span id="current-lang">FR</span>
                                                    <?php else: ?>
                                                        <img src="<?= base_url('assets/frontend/images/flags/en.svg') ?>" alt="English" style="width: 20px; height: 15px; margin-right: 5px;">
                                                        <span id="current-lang">EN</span>
                                                    <?php endif; ?>
                                                </a>
                                                <ul class="sub-menu" style="min-width: 140px;">
                                                    <li><a href="<?= base_url('language/switch/en') ?>" style="display: flex; align-items: center; white-space: nowrap;">
                                                        <img src="<?= base_url('assets/frontend/images/flags/en.svg') ?>" alt="English" style="width: 20px; height: 15px; margin-right: 8px;">
                                                        <span>English</span>
                                                    </a></li>
                                                    <li><a href="<?= base_url('language/switch/fr') ?>" style="display: flex; align-items: center; white-space: nowrap;">
                                                        <img src="<?= base_url('assets/frontend/images/flags/fr.svg') ?>" alt="Français" style="width: 20px; height: 15px; margin-right: 8px;">
                                                        <span>Français</span>
                                                    </a></li>
                                                </ul>
                                            </li>                                               
                                        </ul>
                                    </nav>
                                </div>
                            </div>                           
                        </div>
                    </div>
                </div>
            </div>
        </header>