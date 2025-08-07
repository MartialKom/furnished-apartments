<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="<?= base_url('/admin') ?>" class="b-brand">
                <img src="<?= base_url('assets/admin/images/logo-full.png') ?>" alt="Logo" class="logo logo-lg" />
                <img src="<?= base_url('assets/admin/images/logo-abbr.png') ?>" alt="Logo" class="logo logo-sm" />
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption">
                    <label>Navigation</label>
                </li>
                <li class="nxl-item nxl-hasmenu">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Dashboard</span>
                        <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link" href="<?= base_url('/admin') ?>">Main Dashboard</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="<?= base_url('/admin/analytics') ?>">Analytics</a></li>
                    </ul>
                </li>
                
                <li class="nxl-item nxl-caption">
                    <label>Apartment Management</label>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/apartments') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-home"></i></span>
                        <span class="nxl-mtext">Apartments</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/bookings') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-calendar"></i></span>
                        <span class="nxl-mtext">Bookings</span>
                    </a>
                </li>
                <li class="nxl-item nxl-hasmenu">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-users"></i></span>
                        <span class="nxl-mtext">Customers</span>
                        <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link" href="<?= base_url('/admin/customers') ?>">All Customers</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="<?= base_url('/admin/customers/create') ?>">Add Customer</a></li>
                    </ul>
                </li>
                
                <li class="nxl-item nxl-caption">
                    <label>Business Management</label>
                </li>
                <li class="nxl-item nxl-hasmenu">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-target"></i></span>
                        <span class="nxl-mtext">Leads</span>
                        <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link" href="<?= base_url('/admin/leads') ?>">All Leads</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="<?= base_url('/admin/leads/create') ?>">Add Lead</a></li>
                    </ul>
                </li>
                <li class="nxl-item nxl-hasmenu">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-briefcase"></i></span>
                        <span class="nxl-mtext">Projects</span>
                        <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link" href="<?= base_url('/admin/projects') ?>">All Projects</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="<?= base_url('/admin/projects/create') ?>">Create Project</a></li>
                    </ul>
                </li>
                
                <li class="nxl-item nxl-caption">
                    <label>Reports & Analytics</label>
                </li>
                <li class="nxl-item nxl-hasmenu">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-bar-chart-2"></i></span>
                        <span class="nxl-mtext">Reports</span>
                        <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link" href="<?= base_url('/admin/reports/sales') ?>">Sales Report</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="<?= base_url('/admin/reports/leads') ?>">Leads Report</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="<?= base_url('/admin/reports/projects') ?>">Projects Report</a></li>
                    </ul>
                </li>
                
                <li class="nxl-item nxl-caption">
                    <label>System</label>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/settings') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-settings"></i></span>
                        <span class="nxl-mtext">Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>