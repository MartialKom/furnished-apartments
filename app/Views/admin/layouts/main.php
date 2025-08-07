<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="<?= isset($description) ? $description : 'Admin Dashboard - Furnished Apartments' ?>" />
    <meta name="keyword" content="" />
    <meta name="author" content="Furnished Apartments" />
    
    <title><?= isset($title) ? $title : 'Admin Dashboard - Furnished Apartments' ?></title>
    
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets/admin/images/favicon.ico') ?>" />
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/admin/css/bootstrap.min.css') ?>" />
    
    <!-- Vendors CSS -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/admin/css/vendors.min.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/admin/css/daterangepicker.min.css') ?>" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/admin/css/theme.min.css') ?>" />
</head>

<body>
    <!-- Navigation Menu -->
    <?= $this->include('admin/partials/sidebar') ?>

    <!-- Main Content -->
    <main class="nxl-container">
        <!-- Header -->
        <?= $this->include('admin/partials/header') ?>
        
        <!-- Main Content -->
        <div class="nxl-content">
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10"><?= isset($page_title) ? $page_title : 'Dashboard' ?></h5>
                    </div>
                    <ul class="breadcrumb">
                        <?= isset($breadcrumbs) ? $breadcrumbs : '<li class="breadcrumb-item">Dashboard</li>' ?>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex d-md-none">
                            <a href="javascript:void(0)" class="page-header-right-close-toggle">
                                <i class="feather-arrow-left me-2"></i>
                                <span>Back</span>
                            </a>
                        </div>
                        <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                            <?= isset($header_actions) ? $header_actions : '' ?>
                        </div>
                    </div>
                    <div class="d-md-none d-flex align-items-center">
                        <a href="javascript:void(0)" class="page-header-right-open-toggle">
                            <i class="feather-align-right fs-20"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Page Content -->
            <div class="main-content">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
        
        <!-- Footer -->
        <?= $this->include('admin/partials/footer') ?>
    </main>

    <!-- Scripts -->
    <script src="<?= base_url('assets/admin/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/admin/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/admin/js/vendors.min.js') ?>"></script>
    <script src="<?= base_url('assets/admin/js/common-init.min.js') ?>"></script>
    <script src="<?= base_url('assets/admin/js/dashboard-init.min.js') ?>"></script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>