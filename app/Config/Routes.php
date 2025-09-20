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

// Admin Authentication Routes
$routes->group('admin/auth', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('attempt-login', 'AuthController::attemptLogin');
    $routes->get('logout', 'AuthController::logout');
});

// Language Routes
$routes->group('language', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('switch/(:alpha)', 'LanguageController::switch/$1');
});

// Reservation Routes
$routes->group('reservation', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'ReservationController::index');
    $routes->post('create', 'ReservationController::create');
    $routes->get('check-availability', 'ReservationController::checkAvailability');
});

// Admin Routes (Protected)
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'auth'], function ($routes) {
    // Dashboard - accessible à tous les utilisateurs authentifiés
    $routes->get('/', 'DashboardController::index');
    $routes->get('dashboard', 'DashboardController::index');
    
    // Routes réservées aux administrateurs uniquement
    $routes->group('', ['filter' => 'auth:admin'], function ($routes) {
        $routes->get('analytics', 'DashboardController::analytics');
        
        // User Management - Admin only
        $routes->get('utilisateurs', 'UtilisateurController::index');
        $routes->post('utilisateurs/create', 'UtilisateurController::create');
        $routes->get('utilisateurs/get/(:num)', 'UtilisateurController::get/$1');
        $routes->post('utilisateurs/update/(:num)', 'UtilisateurController::update/$1');
        $routes->post('utilisateurs/toggle-status/(:num)', 'UtilisateurController::toggleStatus/$1');
        $routes->delete('utilisateurs/delete/(:num)', 'UtilisateurController::delete/$1');
        
        // Reports - Admin only
        $routes->get('reports', 'DashboardController::reports');
        $routes->get('reports/sales', 'DashboardController::reports');
        $routes->get('reports/leads', 'DashboardController::reports');
        $routes->get('reports/projects', 'DashboardController::reports');
        
        // Settings - Admin only
        $routes->get('settings', 'DashboardController::settings');
        
        // Customers - Admin only
        $routes->get('customers', 'DashboardController::customers');
        $routes->get('customers/create', 'DashboardController::createCustomer');
        $routes->get('customers/(:num)', 'DashboardController::viewCustomer/$1');
        
        // Leads - Admin only
        $routes->get('leads', 'DashboardController::leads');
        $routes->get('leads/create', 'DashboardController::createLead');
        $routes->get('leads/(:num)', 'DashboardController::viewLead/$1');
        
        // Projects - Admin only
        $routes->get('projects', 'DashboardController::projects');
        $routes->get('projects/create', 'DashboardController::createProject');
        $routes->get('projects/(:num)', 'DashboardController::viewProject/$1');
    });
    
    // Routes accessibles aux gestionnaires et admins
    $routes->group('', ['filter' => 'auth:appartements'], function ($routes) {
        // Appartement Management
        $routes->get('appartements', 'AppartementController::index');
        $routes->post('appartements/create', 'AppartementController::create');
        $routes->get('appartements/get/(:num)', 'AppartementController::get/$1');
        $routes->post('appartements/update/(:num)', 'AppartementController::update/$1');
        $routes->post('appartements/toggle-status/(:num)', 'AppartementController::toggleStatus/$1');
        $routes->post('appartements/toggle-type/(:num)', 'AppartementController::toggleType/$1');
        $routes->delete('appartements/delete/(:num)', 'AppartementController::delete/$1');
    });
    
    $routes->group('', ['filter' => 'auth:reservations'], function ($routes) {
        // Reservation Management
        $routes->get('reservations', 'ReservationController::index');
        $routes->post('reservations/create', 'ReservationController::create');
        $routes->post('reservations/confirmer/(:num)', 'ReservationController::confirmer/$1');
        $routes->post('reservations/annuler/(:num)', 'ReservationController::annuler/$1');
        $routes->post('reservations/add-payment/(:num)', 'ReservationController::addPayment/$1');
        $routes->get('reservations/get/(:num)', 'ReservationController::get/$1');
        $routes->get('reservations/payments/(:num)', 'ReservationController::getPayments/$1');
        $routes->post('reservations/calculate-price', 'ReservationController::calculatePrice');
        
        // Contrats de locataires à long terme
        $routes->get('contrats', 'ContratLocataireController::index');
        $routes->get('contrats/create', 'ContratLocataireController::create');
        $routes->post('contrats/store', 'ContratLocataireController::store');
        $routes->get('contrats/show/(:num)', 'ContratLocataireController::show/$1');
        $routes->get('contrats/edit/(:num)', 'ContratLocataireController::edit/$1');
        $routes->post('contrats/update/(:num)', 'ContratLocataireController::update/$1');
        $routes->post('contrats/terminer/(:num)', 'ContratLocataireController::terminer/$1');
        $routes->post('contrats/generer-echeances/(:num)', 'ContratLocataireController::genererEcheances/$1');
        $routes->get('contrats/echeances-proches', 'ContratLocataireController::getEcheancesProches');
        $routes->get('contrats/retards', 'ContratLocataireController::getRetards');
        
        // Paiements mensuels
        $routes->get('paiements-mensuels', 'PaiementMensuelController::index');
        $routes->get('paiements-mensuels/dashboard', 'PaiementMensuelController::dashboard');
        $routes->get('paiements-mensuels/show/(:num)', 'PaiementMensuelController::show/$1');
        $routes->post('paiements-mensuels/enregistrer', 'PaiementMensuelController::enregistrerPaiement');
        $routes->get('paiements-mensuels/contrat/(:num)', 'PaiementMensuelController::getPaiementsContrat/$1');
        $routes->get('paiements-mensuels/echeance/(:num)', 'PaiementMensuelController::getEcheanceDetails/$1');
        $routes->post('paiements-mensuels/rappel/(:num)', 'PaiementMensuelController::envoyerRappel/$1');
        
        // Reçus de paiement
        $routes->get('receipts/generate/(:num)', 'ReceiptController::generateReceipt/$1');
        $routes->get('receipts/multiple/(:num)/(:any)', 'ReceiptController::generateMultipleReceipt/$1/$2');
        $routes->get('receipts/view/(:num)', 'ReceiptController::viewReceipt/$1');
    });
    
    $routes->group('', ['filter' => 'auth:locataires'], function ($routes) {
        // Locataire Management
        $routes->get('locataires', 'LocataireController::index');
        $routes->post('locataires/create', 'LocataireController::create');
        $routes->get('locataires/get/(:num)', 'LocataireController::get/$1');
        $routes->post('locataires/update/(:num)', 'LocataireController::update/$1');
        $routes->delete('locataires/delete/(:num)', 'LocataireController::delete/$1');
    });
    
    $routes->group('', ['filter' => 'auth:paiements'], function ($routes) {
        // Paiement Management
        $routes->get('paiements', 'PaiementController::index');
        $routes->post('paiements/update-statut/(:num)', 'PaiementController::updateStatut/$1');
        $routes->get('paiements/generer-facture/(:num)', 'PaiementController::genererFacture/$1');
        $routes->get('paiements/get/(:num)', 'PaiementController::get/$1');
    });
});
