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

        // Statistiques supplémentaires pour les admins uniquement
        if ($userRole === 'admin') {
            $utilisateurModel = new \App\Models\UtilisateurModel();
            $paiementModel = new \App\Models\PaiementModel();
            
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
        return view('admin/pages/analytics');
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
        return view('admin/pages/reports');
    }
}