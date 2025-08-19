<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReservationModel;
use App\Models\LocataireModel;
use App\Models\AppartementModel;
use CodeIgniter\HTTP\ResponseInterface;

class ReservationController extends BaseController
{
    protected $reservationModel;
    protected $locataireModel;
    protected $appartementModel;

    public function __construct()
    {
        $this->reservationModel = new ReservationModel();
        $this->locataireModel = new LocataireModel();
        $this->appartementModel = new AppartementModel();
    }

    public function index()
    {
        // Récupérer toutes les réservations avec les informations du locataire et de l'appartement
        $reservations = $this->reservationModel
            ->select('reservations.*, locataires.nom, locataires.telephone, locataires.email, appartements.adresse, appartements.tarifs')
            ->join('locataires', 'locataires.id = reservations.locataire_id')
            ->join('appartements', 'appartements.id = reservations.appartement_id')
            ->orderBy('reservations.created_at', 'DESC')
            ->findAll();

        // Organiser les réservations par statut
        $reservationsParStatut = [
            'en_attente' => [],
            'confirmee' => [],
            'annulee' => []
        ];

        foreach ($reservations as $reservation) {
            $reservationsParStatut[$reservation['statut']][] = $reservation;
        }

        $data = [
            'title' => 'Gestion des Réservations',
            'page_title' => 'Réservations',
            'breadcrumbs' => '<li class="breadcrumb-item">Administration</li><li class="breadcrumb-item active">Réservations</li>',
            'reservations' => $reservationsParStatut
        ];

        return view('admin/pages/reservations/index', $data);
    }

    public function confirmer($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Réservation non trouvée.'
            ]);
        }

        $reservation = $this->reservationModel->find($id);
        if (!$reservation) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Réservation non trouvée.'
            ]);
        }

        if ($reservation['statut'] !== 'en_attente') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Seules les réservations en attente peuvent être confirmées.'
            ]);
        }

        if ($this->reservationModel->update($id, ['statut' => 'confirmee'])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Réservation confirmée avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la confirmation de la réservation.'
            ]);
        }
    }

    public function annuler($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Réservation non trouvée.'
            ]);
        }

        $motif = $this->request->getPost('motif_annulation');
        if (empty($motif)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Le motif d\'annulation est obligatoire.'
            ]);
        }

        $reservation = $this->reservationModel->find($id);
        if (!$reservation) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Réservation non trouvée.'
            ]);
        }

        if ($reservation['statut'] === 'annulee') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cette réservation est déjà annulée.'
            ]);
        }

        $updateData = [
            'statut' => 'annulee',
            'motif_annulation' => $motif
        ];

        if ($this->reservationModel->update($id, $updateData)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Réservation annulée avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de l\'annulation de la réservation.'
            ]);
        }
    }

    public function get($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Réservation non trouvée.'
            ]);
        }

        $reservation = $this->reservationModel
            ->select('reservations.*, locataires.nom, locataires.telephone, locataires.email, appartements.adresse, appartements.tarifs')
            ->join('locataires', 'locataires.id = reservations.locataire_id')
            ->join('appartements', 'appartements.id = reservations.appartement_id')
            ->find($id);

        if (!$reservation) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Réservation non trouvée.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'reservation' => $reservation
        ]);
    }
}