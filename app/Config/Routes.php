<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Frontend Routes
$routes->group('', ['namespace' => 'App\Controllers\Frontend'], function ($routes) {
    $routes->get('/', 'HomeController::index');
    $routes->get('/about', 'HomeController::about');
    $routes->get('/apartments', 'HomeController::apartments');
    $routes->get('/apartments/(:num)', 'HomeController::apartmentDetails/$1');
    $routes->get('/services', 'HomeController::services');
    $routes->get('/gallery', 'HomeController::gallery');
    $routes->get('/pricing', 'HomeController::pricing');
    $routes->get('/team', 'HomeController::team');
    $routes->get('/blog', 'HomeController::blog');
    $routes->get('/blog/(:num)', 'HomeController::blogDetails/$1');
    $routes->get('/contact', 'HomeController::contact');
    $routes->post('/contact', 'HomeController::contact');
});

// Authentication Routes
$routes->group('auth', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('attempt-login', 'AuthController::attemptLogin');
    $routes->get('logout', 'AuthController::logout');
});

// Reservation Routes
$routes->group('reservation', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'ReservationController::index');
    $routes->post('create', 'ReservationController::create');
    $routes->get('check-availability', 'ReservationController::checkAvailability');
});

// Admin Routes (Protected)
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'auth'], function ($routes) {
    $routes->get('/', 'DashboardController::index');
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('analytics', 'DashboardController::analytics');
    
    // Customers
    $routes->get('customers', 'DashboardController::customers');
    $routes->get('customers/create', 'DashboardController::createCustomer');
    $routes->get('customers/(:num)', 'DashboardController::viewCustomer/$1');
    
    // Leads
    $routes->get('leads', 'DashboardController::leads');
    $routes->get('leads/create', 'DashboardController::createLead');
    $routes->get('leads/(:num)', 'DashboardController::viewLead/$1');
    
    // Projects
    $routes->get('projects', 'DashboardController::projects');
    $routes->get('projects/create', 'DashboardController::createProject');
    $routes->get('projects/(:num)', 'DashboardController::viewProject/$1');
    
    // Reports
    $routes->get('reports', 'DashboardController::reports');
    $routes->get('reports/sales', 'DashboardController::reports');
    $routes->get('reports/leads', 'DashboardController::reports');
    $routes->get('reports/projects', 'DashboardController::reports');
    
    // Settings
    $routes->get('settings', 'DashboardController::settings');
});
