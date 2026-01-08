<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= isset($title) ? $title : 'T-Lodge - Premium Furnished Apartments' ?></title>
    <meta name="description" content="<?= isset($description) ? $description : 'T-Lodge - Premium furnished apartments for rent' ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets/frontend/images/favicon.ico') ?>">

    <link rel="stylesheet" href="<?= base_url('template/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('template/css/animate.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('template/css/magnific-popup.css') ?>">
    <link rel="stylesheet" href="<?= base_url('template/css/fontawesome-all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('template/css/dripicons.css') ?>">
    <link rel="stylesheet" href="<?= base_url('template/css/slick.css') ?>">
    <link rel="stylesheet" href="<?= base_url('template/css/default.css') ?>">
    <link rel="stylesheet" href="<?= base_url('template/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('template/css/responsive.css') ?>">
    <style>
        #loader {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            border: 8px solid #f3f3f3; /* Couleur de fond */
            border-top: 8px solid #3498db; /* Couleur du spinner */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

    </style>
</head>
<body>
    <!-- Header -->
    <?= $this->include('frontend/partials/header') ?>

    <!-- Messages Flash -->
    <?php if (session()->getFlashdata('success') || session()->getFlashdata('error')): ?>
        <div id="flash-messages" style="position: fixed; top: 20px; right: 20px; z-index: 9998; max-width: 400px;">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success flash-message" style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); animation: slideInRight 0.5s ease-out;">
                    <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="close" style="float: right; background: none; border: none; font-size: 18px; cursor: pointer; color: #155724;" onclick="this.parentElement.style.display='none'">
                        <span>&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger flash-message" style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); animation: slideInRight 0.5s ease-out;">
                    <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="close" style="float: right; background: none; border: none; font-size: 18px; cursor: pointer; color: #721c24;" onclick="this.parentElement.style.display='none'">
                        <span>&times;</span>
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <style>
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .flash-message {
            transition: all 0.3s ease;
        }

        .flash-message:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.2) !important;
        }
        </style>
    <?php endif; ?>

    <!-- Main Content -->

    <div id="loader">
        <div class="spinner"></div>
    </div>

    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <?= $this->include('frontend/partials/footer') ?>

    <!-- JS here -->

    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="<?= base_url('assets/frontend/js/vendor/jquery-1.12.4.min.js') ?>"></script>
    <script src="<?= base_url('assets/frontend/js/vendor/modernizr-3.5.0.min.js') ?>"></script>
    <script src="<?= base_url('assets/frontend/js/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/frontend/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/frontend/js/one-page-nav-min.js') ?>"></script>
    <script src="<?= base_url('assets/frontend/js/slick.min.js') ?>"></script>
    <script src="<?= base_url('assets/frontend/js/ajax-form.js') ?>"></script>
    <script src="<?= base_url('assets/frontend/js/paroller.js') ?>"></script>
    <script src="<?= base_url('assets/frontend/js/wow.min.js') ?>"></script>
    <script src="<?= base_url('assets/frontend/js/js_isotope.pkgd.min.js') ?>"></script>
    <script src="<?= base_url('assets/frontend/js/imagesloaded.min.js') ?>"></script>
    <script src="<?= base_url('assets/frontend/js/parallax.min.js') ?>"></script>
    <script src="<?= base_url('assets/frontend/js/jquery.waypoints.min.js') ?>"></script>
    <script src="<?= base_url('assets/frontend/js/jquery.counterup.min.js') ?>"></script>
    <script src="<?= base_url('assets/frontend/js/jquery.scrollUp.min.js') ?>"></script>
    <script src="<?= base_url('assets/frontend/js/main.js') ?>"></script>
    <script src="<?= base_url('assets/frontend/js/jquery.magnific-popup.min.js') ?>"></script>
    <script src="<?= base_url('assets/frontend/js/element-in-view.js') ?>"></script>

    <script>
        $(document).ready(function() {
            // Masquer le loader une fois que la page est complètement chargée
            $("#loader").fadeOut("slow");
            
            // Auto-hide flash messages after 6 seconds
            setTimeout(function() {
                $('.flash-message').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 6000);
        });
        $(window).on('load', function() {
            $("#loader").fadeOut("slow");
        });
    </script>

</body>
</html>