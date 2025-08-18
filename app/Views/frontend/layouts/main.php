<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= isset($title) ? $title : 'Furnished Apartments - Premium Real Estate' ?></title>
    <meta name="description" content="<?= isset($description) ? $description : 'Premium furnished apartments for rent' ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets/frontend/images/favicon.ico') ?>">

    <!-- CSS here -->
    <link rel="stylesheet" href="/template/css/bootstrap.min.css">
    <link rel="stylesheet" href="/template/css/animate.min.css">
    <link rel="stylesheet" href="/template/css/magnific-popup.css">
    <link rel="stylesheet" href="/template/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/template/css/dripicons.css">
    <link rel="stylesheet" href="/template/css/slick.css">
    <link rel="stylesheet" href="/template/css/default.css">
    <link rel="stylesheet" href="/template/css/style.css">
    <link rel="stylesheet" href="/template/css/responsive.css">
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
    <script src="/template/js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="/template/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="/template/js/popper.min.js"></script>
    <script src="/template/js/bootstrap.min.js"></script>
    <script src="/template/js/one-page-nav-min.js"></script>
    <script src="/template/js/slick.min.js"></script>
    <script src="/template/js/ajax-form.js"></script>
    <script src="/template/js/paroller.js"></script>
    <script src="/template/js/wow.min.js"></script>
    <script src="/template/js/js_isotope.pkgd.min.js"></script>
    <script src="/template/js/imagesloaded.min.js"></script>
    <script src="/template/js/parallax.min.js"></script>
    <script src="/template/js/jquery.waypoints.min.js"></script>
    <script src="/template/js/jquery.counterup.min.js"></script>
    <script src="/template/js/jquery.scrollUp.min.js"></script>
    <script src="/template/js/parallax-scroll.js"></script>
    <script src="/template/js/jquery.magnific-popup.min.js"></script>
    <script src="/template/js/element-in-view.js"></script>
    <script src="/template/js/main.js"></script>
</body>
</html>