<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-1">Welcome back, Administrator!</h5>
                        <p class="fs-12 fw-medium text-muted mb-0">Here's what's happening with your apartment rentals today.</p>
                    </div>
                    <div class="avatar-text avatar-lg bg-primary text-white">
                        <i class="feather-home"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Total Apartments -->
    <div class="col-xxl-3 col-md-6">
        <div class="card stretch stretch-full">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-4">
                    <div class="content-area">
                        <h4 class="fw-bolder mb-0">248</h4>
                        <p class="fs-12 fw-medium text-muted mb-0">Total Apartments</p>
                    </div>
                    <div class="avatar-text avatar-lg bg-gray-200">
                        <i class="feather-home"></i>
                    </div>
                </div>
                <div class="progress-1">
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 89%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-1">
                        <div class="text-start">
                            <p class="fs-12 fw-medium text-muted mb-0">Available: 32</p>
                        </div>
                        <div class="text-end">
                            <p class="fs-12 fw-medium text-success mb-0">89% Occupied</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Active Bookings -->
    <div class="col-xxl-3 col-md-6">
        <div class="card stretch stretch-full">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-4">
                    <div class="content-area">
                        <h4 class="fw-bolder mb-0">156</h4>
                        <p class="fs-12 fw-medium text-muted mb-0">Active Bookings</p>
                    </div>
                    <div class="avatar-text avatar-lg bg-warning text-white">
                        <i class="feather-calendar"></i>
                    </div>
                </div>
                <div class="progress-1">
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 76%" aria-valuenow="76" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-1">
                        <div class="text-start">
                            <p class="fs-12 fw-medium text-muted mb-0">This month: 24</p>
                        </div>
                        <div class="text-end">
                            <p class="fs-12 fw-medium text-success mb-0">+12%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Monthly Revenue -->
    <div class="col-xxl-3 col-md-6">
        <div class="card stretch stretch-full">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-4">
                    <div class="content-area">
                        <h4 class="fw-bolder mb-0">$45,280</h4>
                        <p class="fs-12 fw-medium text-muted mb-0">Monthly Revenue</p>
                    </div>
                    <div class="avatar-text avatar-lg bg-success text-white">
                        <i class="feather-dollar-sign"></i>
                    </div>
                </div>
                <div class="progress-1">
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 84%" aria-valuenow="84" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-1">
                        <div class="text-start">
                            <p class="fs-12 fw-medium text-muted mb-0">Target: $54k</p>
                        </div>
                        <div class="text-end">
                            <p class="fs-12 fw-medium text-success mb-0">+8.2%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- New Customers -->
    <div class="col-xxl-3 col-md-6">
        <div class="card stretch stretch-full">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-4">
                    <div class="content-area">
                        <h4 class="fw-bolder mb-0">32</h4>
                        <p class="fs-12 fw-medium text-muted mb-0">New Customers</p>
                    </div>
                    <div class="avatar-text avatar-lg bg-danger text-white">
                        <i class="feather-users"></i>
                    </div>
                </div>
                <div class="progress-1">
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 68%" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-1">
                        <div class="text-start">
                            <p class="fs-12 fw-medium text-muted mb-0">This week: 8</p>
                        </div>
                        <div class="text-end">
                            <p class="fs-12 fw-medium text-success mb-0">+15.3%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities and Quick Actions -->
<div class="row">
    <div class="col-xl-8">
        <div class="card stretch stretch-full">
            <div class="card-header">
                <h5 class="card-title">Recent Bookings</h5>
            </div>
            <div class="card-body custom-card-action p-0">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Apartment</th>
                                <th>Check-in</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-text">JD</div>
                                        <div class="d-flex flex-column">
                                            <a href="javascript:void(0);" class="fw-semibold text-dark mb-0">John Doe</a>
                                            <span class="fs-12 fw-medium text-muted">john@example.com</span>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="fw-semibold">Luxury Suite #201</span></td>
                                <td><span class="fs-12 text-muted">Dec 15, 2024</span></td>
                                <td><span class="fs-12 text-muted">3 months</span></td>
                                <td><div class="badge bg-soft-success text-success">Active</div></td>
                                <td><span class="fw-semibold">$3,200</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-text">JS</div>
                                        <div class="d-flex flex-column">
                                            <a href="javascript:void(0);" class="fw-semibold text-dark mb-0">Jane Smith</a>
                                            <span class="fs-12 fw-medium text-muted">jane@example.com</span>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="fw-semibold">Modern Studio #105</span></td>
                                <td><span class="fs-12 text-muted">Dec 18, 2024</span></td>
                                <td><span class="fs-12 text-muted">6 months</span></td>
                                <td><div class="badge bg-soft-warning text-warning">Pending</div></td>
                                <td><span class="fw-semibold">$1,850</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="card stretch stretch-full">
            <div class="card-header">
                <h5 class="card-title">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="<?= base_url('/admin/apartments/create') ?>" class="btn btn-primary">
                        <i class="feather-plus me-2"></i>Add New Apartment
                    </a>
                    <a href="<?= base_url('/admin/bookings/create') ?>" class="btn btn-warning">
                        <i class="feather-calendar me-2"></i>Create Booking
                    </a>
                    <a href="<?= base_url('/admin/customers/create') ?>" class="btn btn-success">
                        <i class="feather-user-plus me-2"></i>Add Customer
                    </a>
                    <a href="<?= base_url('/admin/reports') ?>" class="btn btn-info">
                        <i class="feather-bar-chart-2 me-2"></i>View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>