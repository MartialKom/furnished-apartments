<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= isset($title) ? $title : 'Furnished Apartments - Premium Real Estate' ?></title>
    <meta name="description" content="<?= isset($description) ? $description : 'Premium furnished apartments for rent' ?>">
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
</head>
<body>
    <!-- Header -->
    <?= $this->include('frontend/partials/header') ?>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <?= $this->include('frontend/partials/footer') ?>

    <!-- JS here -->
    <script src="<?= base_url('assets/frontend/js/vendor/modernizr-3.5.0.min.js') ?>"></script>
    <script src="<?= base_url('assets/frontend/js/vendor/jquery-1.12.4.min.js') ?>"></script>
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

</body>
</html>