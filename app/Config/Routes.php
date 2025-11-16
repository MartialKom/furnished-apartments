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
        $routes->get('settings', 'ParametresController::index');
        
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

        // Test Email - Admin only
        $routes->get('test-email', 'TestEmailController::index');
        $routes->post('test-email/send-test', 'TestEmailController::sendTest');
        $routes->post('test-email/send-test-notification', 'TestEmailController::sendTestNotification');
        $routes->get('test-email/show-config', 'TestEmailController::showConfig');
    });
    
    // Routes accessibles aux gestionnaires et admins (Rapports & Analytics)
    $routes->get('analytics', 'DashboardController::analytics');
    $routes->get('rapports', 'RapportController::index');
    $routes->post('rapports/create', 'RapportController::create');
    $routes->get('rapports/get/(:num)', 'RapportController::get/$1');
    $routes->post('rapports/update/(:num)', 'RapportController::update/$1');
    $routes->delete('rapports/delete/(:num)', 'RapportController::delete/$1');
    $routes->get('rapports/download/(:num)', 'RapportController::downloadDocument/$1');

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

        // Factures d'eau
        $routes->get('factures-eau', 'FactureEauController::index');
        $routes->get('factures-eau/create', 'FactureEauController::create');
        $routes->post('factures-eau/store', 'FactureEauController::store');
        $routes->get('factures-eau/get/(:num)', 'FactureEauController::get/$1');
        $routes->post('factures-eau/marquer-paye/(:num)', 'FactureEauController::marquerPaye/$1');
        $routes->post('factures-eau/update/(:num)', 'FactureEauController::update/$1');
        $routes->delete('factures-eau/delete/(:num)', 'FactureEauController::delete/$1');
        $routes->post('factures-eau/generer-mois', 'FactureEauController::genererFacturesMois');
        $routes->post('factures-eau/update-retards', 'FactureEauController::updateStatutsRetard');

        // Gestion des voitures
        $routes->get('voitures', 'VoitureController::index');
        $routes->post('voitures/store', 'VoitureController::store');
        $routes->get('voitures/get/(:num)', 'VoitureController::get/$1');
        $routes->post('voitures/update/(:num)', 'VoitureController::update/$1');
        $routes->delete('voitures/delete/(:num)', 'VoitureController::delete/$1');
        $routes->post('voitures/changer-statut/(:num)', 'VoitureController::changerStatut/$1');

        // Locations de voitures
        $routes->get('locations-voitures', 'LocationVoitureController::index');
        $routes->post('locations-voitures/store', 'LocationVoitureController::store');
        $routes->get('locations-voitures/get/(:num)', 'LocationVoitureController::get/$1');
        $routes->post('locations-voitures/demarrer/(:num)', 'LocationVoitureController::demarrer/$1');
        $routes->post('locations-voitures/terminer/(:num)', 'LocationVoitureController::terminer/$1');
        $routes->post('locations-voitures/annuler/(:num)', 'LocationVoitureController::annuler/$1');
        $routes->post('locations-voitures/paiement/(:num)', 'LocationVoitureController::enregistrerPaiement/$1');

        // Reçus de paiement
        $routes->get('receipts/generate/(:num)', 'ReceiptController::generateReceipt/$1');
        $routes->get('receipts/multiple/(:num)/(:any)', 'ReceiptController::generateMultipleReceipt/$1/$2');
        
        // Contrats de location (Admin uniquement)
        $routes->get('contrats-location', 'ContratController::listContrats');
        $routes->get('contrats-location/generate/(:num)', 'ContratController::generateContrat/$1');
        
        // Paramètres de la structure (Admin uniquement)
        $routes->get('parametres', 'ParametresController::index');
        $routes->post('parametres/update', 'ParametresController::update');
        $routes->post('parametres/add-param', 'ParametresController::addParam');
        $routes->delete('parametres/delete/(:num)', 'ParametresController::deleteParam/$1');
        $routes->get('parametres/get/(:any)', 'ParametresController::getParam/$1');
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

    // Routes pour le module Stock
    $routes->group('stock', ['filter' => 'auth:stock'], function ($routes) {
        // Dashboard Stock
        $routes->get('/', 'StockController::index');
        $routes->get('dashboard', 'StockController::index');

        // Catégories
        $routes->get('categories', 'StockController::categories');
        $routes->post('categories/create', 'StockController::createCategorie');
        $routes->get('categories/get/(:num)', 'StockController::getCategorie/$1');
        $routes->post('categories/update/(:num)', 'StockController::updateCategorie/$1');
        $routes->post('categories/toggle-status/(:num)', 'StockController::toggleCategorieStatus/$1');
        $routes->delete('categories/delete/(:num)', 'StockController::deleteCategorie/$1');

        // Produits
        $routes->get('produits', 'StockController::produits');
        $routes->post('produits/create', 'StockController::createProduit');
        $routes->get('produits/get/(:num)', 'StockController::getProduit/$1');
        $routes->post('produits/update/(:num)', 'StockController::updateProduit/$1');
        $routes->post('produits/toggle-status/(:num)', 'StockController::toggleProduitStatus/$1');
        $routes->delete('produits/delete/(:num)', 'StockController::deleteProduit/$1');

        // Approvisionnements
        $routes->get('approvisionnements', 'StockController::approvisionnements');
        $routes->post('approvisionnements/create', 'StockController::createApprovisionnement');
        $routes->get('approvisionnements/get/(:num)', 'StockController::getApprovisionnement/$1');

        // Sorties
        $routes->get('sorties', 'StockController::sorties');
        $routes->post('sorties/create', 'StockController::createSortie');
        $routes->get('sorties/get/(:num)', 'StockController::getSortie/$1');

        // Inventaires
        $routes->get('inventaires', 'StockController::inventaires');
        $routes->post('inventaires/create', 'StockController::createInventaire');
        $routes->get('inventaires/show/(:num)', 'StockController::showInventaire/$1');
        $routes->post('inventaires/update-stock-physique', 'StockController::updateStockPhysique');
        $routes->post('inventaires/terminer/(:num)', 'StockController::terminerInventaire/$1');
        $routes->post('inventaires/valider/(:num)', 'StockController::validerInventaire/$1');

        // Rapports
        $routes->get('rapports', 'StockController::rapports');
        $routes->get('rapports/imprimer-stock', 'StockController::imprimerStock');
        $routes->get('rapports/imprimer-sorties', 'StockController::imprimerSorties');
    });
});
