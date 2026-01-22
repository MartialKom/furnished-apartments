<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AppartementModel;
use App\Models\ReservationModel;
use App\Models\LocataireModel;

class DashboardController extends BaseController
{
    protected $appartementModel;
    protected $reservationModel;
    protected $locataireModel;

    public function __construct()
    {
        $this->appartementModel = new AppartementModel();
        $this->reservationModel = new ReservationModel();
        $this->locataireModel = new LocataireModel();
    }

    public function index()
    {
        $session = session();
        $userRole = $session->get('user_role');
        
        // Statistiques des appartements
        $totalAppartements = $this->appartementModel->countAllResults();
        $appartementsDisponibles = $this->appartementModel->where('statut', 'disponible')->countAllResults();
        $appartementsMaintenance = $this->appartementModel->where('statut', 'maintenance')->countAllResults();
        $appartementsOccupes = $this->appartementModel->where('statut', 'occupe')->countAllResults();

        // Statistiques des réservations
        $reservationsEnAttente = $this->reservationModel->where('statut', 'en_attente')->countAllResults();
        $reservationsConfirmees = $this->reservationModel->where('statut', 'confirmee')->countAllResults();
        $reservationsAnnulees = $this->reservationModel->where('statut', 'annulee')->countAllResults();
        $totalReservations = $this->reservationModel->countAllResults();

        // Nouveaux locataires ce mois
        $nouveauxLocataires = $this->locataireModel->where('created_at >=', date('Y-m-01'))->countAllResults();

        // Récentes réservations avec détails
        $recentesReservations = $this->reservationModel->getReservationsWithDetails();
        if (!empty($recentesReservations)) {
            $recentesReservations = array_slice($recentesReservations, 0, 5);
        }

        // Statistiques selon le rôle
        $stats = [
            'total_appartements' => $totalAppartements,
            'appartements_disponibles' => $appartementsDisponibles,
            'appartements_maintenance' => $appartementsMaintenance,
            'appartements_occupes' => $appartementsOccupes,
            'reservations_en_attente' => $reservationsEnAttente,
            'reservations_confirmees' => $reservationsConfirmees,
            'reservations_annulees' => $reservationsAnnulees,
            'total_reservations' => $totalReservations,
            'nouveaux_locataires' => $nouveauxLocataires
        ];

        // Statistiques de ventes pour gestionnaire et admin
        $paiementModel = new \App\Models\PaiementModel();
        $paiementMensuelModel = new \App\Models\PaiementMensuelModel();

        // Ventes du mois en cours (réservations)
        $ventesMoisCourant = $paiementModel
            ->select('SUM(montant) as total, COUNT(id) as nb_paiements')
            ->where('statut', 'paye')
            ->where('date >=', date('Y-m-01'))
            ->where('date <=', date('Y-m-t'))
            ->first();

        // Loyers du mois en cours
        $loyersMoisCourant = $paiementMensuelModel
            ->select('SUM(montant_paye) as total, COUNT(id) as nb_paiements')
            ->where('statut', 'paye')
            ->where('date_paiement >=', date('Y-m-01'))
            ->where('date_paiement <=', date('Y-m-t'))
            ->first();

        $stats['ventes_mois'] = [
            'reservations' => $ventesMoisCourant['total'] ?? 0,
            'nb_paiements_reservations' => $ventesMoisCourant['nb_paiements'] ?? 0,
            'loyers' => $loyersMoisCourant['total'] ?? 0,
            'nb_paiements_loyers' => $loyersMoisCourant['nb_paiements'] ?? 0,
            'total' => ($ventesMoisCourant['total'] ?? 0) + ($loyersMoisCourant['total'] ?? 0),
        ];

        // Paiements en attente
        $paiementsEnAttente = $paiementMensuelModel
            ->select('SUM(montant_du - montant_paye) as total_attente, COUNT(id) as nb_attente')
            ->where('statut', 'en_attente')
            ->orWhere('statut', 'en_retard')
            ->first();

        $stats['paiements_attente'] = $paiementsEnAttente['total_attente'] ?? 0;
        $stats['nb_paiements_attente'] = $paiementsEnAttente['nb_attente'] ?? 0;

        // Statistiques supplémentaires pour les admins uniquement
        if ($userRole === 'admin') {
            $utilisateurModel = new \App\Models\UtilisateurModel();

            $stats['total_utilisateurs'] = $utilisateurModel->countAllResults();
            $stats['utilisateurs_actifs'] = $utilisateurModel->where('statut', 'actif')->countAllResults();
            $stats['total_paiements'] = $paiementModel->countAllResults();
            $stats['paiements_payes'] = $paiementModel->where('statut', 'paye')->countAllResults();
            $stats['paiements_en_attente'] = $paiementModel->where('statut', 'en_attente')->countAllResults();
        }

        $data = [
            'user_role' => $userRole,
            'stats' => $stats,
            'recentes_reservations' => $recentesReservations
        ];

        return view('admin/pages/dashboard', $data);
    }

    public function analytics()
    {
        $session = session();
        $userRole = $session->get('user_role');

        $paiementModel = new \App\Models\PaiementModel();
        $paiementMensuelModel = new \App\Models\PaiementMensuelModel();
        $rapportModel = new \App\Models\RapportModel();

        // Période par défaut: 12 mois
        $nombreMois = $this->request->getGet('periode') ?: 12;

        // === CASH FLOW (CASH-IN vs CASH-OUT) ===
        $cashFlow = $rapportModel->getResumeCashFlow($nombreMois);
        $totauxPeriode = $rapportModel->getTotauxPeriode($nombreMois);

        // === STATISTIQUES GLOBALES ===

        // Taux d'occupation des appartements
        $totalAppartements = $this->appartementModel->countAllResults();
        $appartementsOccupes = $this->appartementModel->where('statut', 'occupe')->countAllResults();
        $tauxOccupation = $totalAppartements > 0 ? ($appartementsOccupes / $totalAppartements) * 100 : 0;

        // Taux de conversion des réservations
        $totalReservations = $this->reservationModel->countAllResults();
        $reservationsConfirmees = $this->reservationModel->where('statut', 'confirmee')->countAllResults();
        $tauxConversion = $totalReservations > 0 ? ($reservationsConfirmees / $totalReservations) * 100 : 0;

        // Revenu moyen par réservation
        $revenuReservations = $paiementModel
            ->select('AVG(paiements.montant) as revenu_moyen, SUM(paiements.montant) as revenu_total')
            ->where('statut', 'paye')
            ->first();

        // Revenu moyen par contrat
        $revenuContrats = $paiementMensuelModel
            ->select('AVG(montant_paye) as revenu_moyen, SUM(montant_paye) as revenu_total')
            ->where('statut', 'paye')
            ->first();

        // === TENDANCES SUR 6 MOIS ===
        $tendances = [];
        for ($i = 5; $i >= 0; $i--) {
            $mois = date('Y-m', strtotime("-$i months"));
            $debutMois = $mois . '-01';
            $finMois = date('Y-m-t', strtotime($debutMois));

            // Réservations du mois
            $reservationsMois = $this->reservationModel
                ->where('created_at >=', $debutMois)
                ->where('created_at <=', $finMois)
                ->countAllResults();

            // Revenus du mois (réservations + loyers)
            $revenusReservations = $paiementModel
                ->select('SUM(montant) as total')
                ->where('statut', 'paye')
                ->where('date >=', $debutMois)
                ->where('date <=', $finMois)
                ->first();

            $revenusLoyers = $paiementMensuelModel
                ->select('SUM(montant_paye) as total')
                ->where('statut', 'paye')
                ->where('date_paiement >=', $debutMois)
                ->where('date_paiement <=', $finMois)
                ->first();

            $tendances[] = [
                'mois' => $mois,
                'mois_libelle' => strftime('%B %Y', strtotime($debutMois)),
                'nb_reservations' => $reservationsMois,
                'revenus' => ($revenusReservations['total'] ?? 0) + ($revenusLoyers['total'] ?? 0),
            ];
        }

        // === TOP 5 APPARTEMENTS LES PLUS RENTABLES ===
        $topAppartements = $this->reservationModel
            ->select('appartements.adresse, COUNT(reservations.id) as nb_reservations, SUM(reservations.montant_total) as revenu_total')
            ->join('appartements', 'appartements.id = reservations.appartement_id')
            ->where('reservations.statut !=', 'annulee')
            ->groupBy('appartements.id')
            ->orderBy('revenu_total', 'DESC')
            ->limit(5)
            ->findAll();

        // === PAIEMENTS EN RETARD ===
        $paiementsRetard = $paiementMensuelModel
            ->where('statut', 'en_retard')
            ->orWhere('statut', 'partiellement_paye')
            ->countAllResults();

        $montantRetard = $paiementMensuelModel
            ->select('SUM(montant_du - montant_paye) as total_retard')
            ->where('statut', 'en_retard')
            ->orWhere('statut', 'partiellement_paye')
            ->first();

        $data = [
            'title' => 'Analytics Globale',
            'page_title' => 'Analytics',
            'breadcrumbs' => '<li class="breadcrumb-item">Rapports & Analyse</li><li class="breadcrumb-item active">Analytics</li>',
            'user_role' => $userRole,
            'periode_mois' => $nombreMois,
            // Cash Flow
            'cash_flow' => $cashFlow,
            'totaux_periode' => $totauxPeriode,
            // Stats globales
            'taux_occupation' => round($tauxOccupation, 2),
            'taux_conversion' => round($tauxConversion, 2),
            'revenu_moyen_reservation' => $revenuReservations['revenu_moyen'] ?? 0,
            'revenu_total_reservations' => $revenuReservations['revenu_total'] ?? 0,
            'revenu_moyen_contrat' => $revenuContrats['revenu_moyen'] ?? 0,
            'revenu_total_contrats' => $revenuContrats['revenu_total'] ?? 0,
            'tendances' => $tendances,
            'top_appartements' => $topAppartements,
            'paiements_retard' => $paiementsRetard,
            'montant_retard' => $montantRetard['total_retard'] ?? 0,
        ];

        return view('admin/pages/analytics', $data);
    }

    public function customers()
    {
        return view('admin/pages/customers');
    }

    public function createCustomer()
    {
        return view('admin/pages/customers_create');
    }

    public function viewCustomer($id = null)
    {
        $data['customer_id'] = $id;
        return view('admin/pages/customers_view', $data);
    }

    public function leads()
    {
        return view('admin/pages/leads');
    }

    public function createLead()
    {
        return view('admin/pages/leads_create');
    }

    public function viewLead($id = null)
    {
        $data['lead_id'] = $id;
        return view('admin/pages/leads_view', $data);
    }

    public function projects()
    {
        return view('admin/pages/projects');
    }

    public function createProject()
    {
        return view('admin/pages/projects_create');
    }

    public function viewProject($id = null)
    {
        $data['project_id'] = $id;
        return view('admin/pages/projects_view', $data);
    }

    public function settings()
    {
        return view('admin/pages/settings');
    }

    public function reports()
    {
        $session = session();
        $userRole = $session->get('user_role');

        // Période par défaut: mois en cours
        $dateDebut = $this->request->getGet('date_debut') ?: date('Y-m-01');
        $dateFin = $this->request->getGet('date_fin') ?: date('Y-m-t');

        $paiementModel = new \App\Models\PaiementModel();
        $paiementMensuelModel = new \App\Models\PaiementMensuelModel();
        $contratModel = new \App\Models\ContratLocataireModel();

        // === STATISTIQUES DE VENTES (RÉSERVATIONS) ===
        $ventesReservations = $paiementModel
            ->select('SUM(paiements.montant) as total_ventes, COUNT(DISTINCT paiements.reservation_id) as nb_reservations, COUNT(paiements.id) as nb_paiements')
            ->join('reservations', 'reservations.id = paiements.reservation_id')
            ->where('paiements.statut', 'paye')
            ->where('paiements.date >=', $dateDebut)
            ->where('paiements.date <=', $dateFin)
            ->first();

        // === STATISTIQUES LOYERS MENSUELS ===
        $loyersMensuels = $paiementMensuelModel
            ->select('SUM(montant_paye) as total_loyers, COUNT(id) as nb_paiements')
            ->where('statut', 'paye')
            ->where('date_paiement >=', $dateDebut)
            ->where('date_paiement <=', $dateFin)
            ->first();

        // === RÉPARTITION PAR APPARTEMENT ===
        $ventesParAppartement = $this->reservationModel
            ->select('appartements.adresse, SUM(reservations.montant_total) as total_reservations, COUNT(reservations.id) as nb_reservations')
            ->join('appartements', 'appartements.id = reservations.appartement_id')
            ->where('reservations.created_at >=', $dateDebut)
            ->where('reservations.created_at <=', $dateFin)
            ->groupBy('appartements.id')
            ->orderBy('total_reservations', 'DESC')
            ->findAll();

        // === ÉVOLUTION MENSUELLE ===
        $evolutionMensuelle = $paiementModel
            ->select('DATE_FORMAT(date, "%Y-%m") as mois, SUM(montant) as total')
            ->where('statut', 'paye')
            ->where('date >=', date('Y-01-01'))
            ->where('date <=', date('Y-12-31'))
            ->groupBy('mois')
            ->orderBy('mois', 'ASC')
            ->findAll();

        // === CONTRATS ACTIFS ===
        $contratsActifs = $contratModel
            ->where('statut', 'actif')
            ->countAllResults();

        $data = [
            'title' => 'Rapports Généraux',
            'user_role' => $userRole,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'ventes_reservations' => [
                'total' => $ventesReservations['total_ventes'] ?? 0,
                'nb_reservations' => $ventesReservations['nb_reservations'] ?? 0,
                'nb_paiements' => $ventesReservations['nb_paiements'] ?? 0,
            ],
            'loyers_mensuels' => [
                'total' => $loyersMensuels['total_loyers'] ?? 0,
                'nb_paiements' => $loyersMensuels['nb_paiements'] ?? 0,
            ],
            'total_global' => ($ventesReservations['total_ventes'] ?? 0) + ($loyersMensuels['total_loyers'] ?? 0),
            'ventes_par_appartement' => $ventesParAppartement,
            'evolution_mensuelle' => $evolutionMensuelle,
            'contrats_actifs' => $contratsActifs,
        ];

        return view('admin/pages/reports', $data);
    }

    /**
     * Page d'accès refusé - affichée quand l'utilisateur n'a aucune permission
     */
    public function accessDenied()
    {
        $session = session();

        // Si pas connecté, rediriger vers login
        if (!$session->get('is_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $data = [
            'title' => 'Accès Refusé',
            'page_title' => 'Accès Refusé',
            'breadcrumbs' => '<li class="breadcrumb-item active">Accès Refusé</li>',
            'user_role_name' => $session->get('user_role_name'),
            'user_permissions' => $session->get('user_permissions') ?? []
        ];

        return view('admin/pages/access_denied', $data);
    }
}