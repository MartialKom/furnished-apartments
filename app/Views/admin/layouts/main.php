<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="<?= isset($description) ? $description : 'Admin Dashboard - NSENOU TOWER' ?>" />
    <meta name="keyword" content="" />
    <meta name="author" content="Furnished Apartments" />
    
    <title><?= isset($title) ? $title : 'Admin Dashboard - NsenouTower' ?></title>
    
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets/admin/images/favicon.ico') ?>" />
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/admin/css/bootstrap.min.css') ?>" />
    
    <!-- Vendors CSS -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/admin/css/vendors.min.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/admin/css/daterangepicker.min.css') ?>" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/admin/css/theme.min.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/admin/css/status-badges.css') ?>" />
    <script src="<?= base_url('assets/admin/js/jquery.min.js') ?>"></script>
    
    <!-- Custom Alignment Fix -->
    <style>
        .nxl-header {
            position: fixed !important;
            top: 0 !important;
            left: 280px !important; /* Width of sidebar */
            right: 0 !important;
            transition: left 0.3s ease !important;
            width: calc(100% - 280px) !important;
        }
        
        /* When sidebar is collapsed */
        body.nxl-ltr-text.nxl-navigation-wrap-compact .nxl-header {
            left: 78px !important; /* Collapsed sidebar width */
            width: calc(100% - 78px) !important;
        }
        
        /* Sidebar positioning */
       
        
        /* Main content area adjustment */
        .nxl-container {
            transition: margin-left 0.3s ease !important;
        }
        
        /* When sidebar is collapsed */
        body.nxl-ltr-text.nxl-navigation-wrap-compact .nxl-container {
            margin-left: 78px !important;
        }
        
        /* Header wrapper alignment */
        .header-wrapper {
            padding-left: 0 !important;
            height: 70px !important;
            display: flex !important;
            align-items: center !important;
        }
        
        /* Sidebar improvements */
        .nxl-navbar .nxl-item .nxl-link {
            padding: 12px 20px !important;
            border-radius: 6px !important;
            margin: 2px 10px !important;
            transition: all 0.3s ease !important;
        }
        
        .nxl-navbar .nxl-item .nxl-link:hover {
            background: rgba(210, 151, 81, 0.1) !important;
            color: #d29751 !important;
        }
        
        .nxl-navbar .nxl-item.active .nxl-link,
        .nxl-navbar .nxl-item .nxl-link.active {
            background: #d29751 !important;
            color: white !important;
        }
        
        /* Caption styling */
        .nxl-navbar .nxl-caption label {
            color: #d29751 !important;
            font-weight: 600 !important;
            font-size: 11px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }
        
        /* Breadcrumb navigation collée à la navbar */
        .nxl-content {
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .nxl-container .nxl-content {
            padding-top: 0 !important;
            margin-top: 0 !important;
        }
        
        

    </style>
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

    
     <script src="<?= base_url('assets/admin/js/vendors.min.js') ?>"></script>

     <script src="<?= base_url('assets/admin/js/daterangepicker.min.js') ?>"></script>
     <script src="<?= base_url('assets/admin/js/apexcharts.min.js') ?>"></script>
     <script src="<?= base_url('assets/admin/js/circle-progress.min.js') ?>"></script>
    
    <script src="<?= base_url('assets/admin/js/common-init.min.js') ?>"></script>
    <script src="<?= base_url('assets/admin/js/dashboard-init.min.js') ?>"></script>
    
    <script src="<?= base_url('assets/admin/js/theme-customizer-init.min.js') ?>"></script>
    
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>