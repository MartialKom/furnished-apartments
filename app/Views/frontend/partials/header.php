<header class="header-area">
            <div id="header-sticky" class="menu-area">
                <div class="container">
                    <div class="second-menu">
                        <div class="row align-items-center">
                            <div class="col-xl-2 col-lg-2">
                                <div class="logo">
                                    <a href="<?= base_url('/home') ?>"><img src="<?= base_url('assets/frontend/images/logo/nsenou.png') ?>" alt="logo"></a>
                                </div>
                            </div>
                            <div class="col-xl-10 col-lg-9">
                                <div class="responsive"><i class="icon dripicons-align-right"></i></div>
                                <div class="main-menu text-right <text-xl-right></text-xl-right>">
                                    <nav id="mobile-menu">
                                        <ul>
                                            <li class="has-sub"><a href="<?= base_url('/home') ?>" ><?= lang('Common.nav_home') ?></a></li>
                                            <li><a href="<?= base_url('about') ?>"><?= lang('Common.nav_about') ?></a></li>
                                            <li><a href="<?= base_url('apartments') ?>"><?= lang('Common.nav_apartments') ?></a></li>
                                            <li><a href="<?= base_url('cars') ?>">Location de Voitures</a></li>
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