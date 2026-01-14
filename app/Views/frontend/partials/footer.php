<footer class="footer-bg footer-p pt-100 pb-80 ">
  
            <div class="footer-top pb-30">
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-xl-6 col-lg-6 col-sm-6">
                            <div class="footer-widget mb-30">
                                <div class="logo mb-35">
                                    <a href="#"><img src="<?= base_url('assets/frontend/images/logo/nsenou.png') ?>" alt="logo"></a>
                                </div>
                                <div class="footer-text mb-20">
                                    <p><?= lang('Common.footer_description') ?></p>
                                </div>
                                <div class="footer-social">
                                    <span><?= lang('Common.follow_us') ?></span>
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-twitter"></i></a>
                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                    <a href="#"><i class="fab fa-google-plus-g"></i></a>
                                </div>
                            </div>
                        </div>
						<div class="col-xl-2 col-lg-3 col-sm-6">
                            <div class="footer-widget mb-30">
                                <div class="f-widget-title">
                                    <h5><?= lang('Common.nsenou_news') ?></h5>
                                </div>
                                <div class="footer-link">
                                    <ul>
										<li><a href="#"><?= lang('Common.nav_about') ?></a></li>
                                        <li><a href="#"><?= lang('Common.reviews') ?></a></li>
                                        <li><a href="#"><?= lang('Common.help') ?></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-sm-6">
                            <div class="footer-widget mb-30">
                                <div class="f-widget-title">
                                    <h5><?= lang('Common.useful_links') ?></h5>
                                </div>
                                <div class="footer-link">
                                    <ul>
                                        <li><a href="#"><?= lang('Common.home_link') ?></a></li>
                                        <li><a href="#"><?= lang('Common.contact_us_link') ?></a></li>
                                        <li><a href="#"><?= lang('Common.services_link') ?></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>                     
                        
                    </div>
                </div>
            </div>
            <div class="copyright-wrap">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="copyright-text text-center">
                                <p><?= lang('Common.copyright_text') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Bouton WhatsApp flottant -->
        <a href="https://wa.me/237671387969" target="_blank" class="whatsapp-float" id="whatsapp-btn">
            <i class="fab fa-whatsapp"></i>
        </a>

        <style>
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background-color: #25D366;
            color: white;
            border-radius: 50%;
            text-align: center;
            font-size: 30px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .whatsapp-float:hover {
            background-color: #128C7E;
            transform: scale(1.1);
            box-shadow: 3px 3px 15px rgba(0, 0, 0, 0.4);
            color: white;
        }

        .whatsapp-float i {
            margin-top: 3px;
        }

        /* Animation de pulsation */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
            }
        }

        .whatsapp-float {
            animation: pulse 2s infinite;
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            .whatsapp-float {
                bottom: 20px;
                right: 20px;
                width: 50px;
                height: 50px;
                font-size: 25px;
            }
        }
        </style>