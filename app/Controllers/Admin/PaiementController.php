<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PaiementModel;
use App\Models\ReservationModel;
use App\Models\LocataireModel;
use CodeIgniter\HTTP\ResponseInterface;

class PaiementController extends BaseController
{
    protected $paiementModel;
    protected $reservationModel;
    protected $locataireModel;

    public function __construct()
    {
        $this->paiementModel = new PaiementModel();
        $this->reservationModel = new ReservationModel();
        $this->locataireModel = new LocataireModel();
    }

    public function index()
    {
        // Récupérer tous les paiements avec les informations de la réservation et du locataire
        $paiements = $this->paiementModel
            ->select('paiements.*, locataires.nom as locataire_nom, locataires.email, reservations.date_debut, reservations.date_fin, appartements.adresse')
            ->join('locataires', 'locataires.id = paiements.locataire_id')
            ->join('reservations', 'reservations.id = paiements.reservation_id')
            ->join('appartements', 'appartements.id = reservations.appartement_id')
            ->orderBy('paiements.created_at', 'DESC')
            ->findAll();

        // Organiser les paiements par statut
        $paiementsParStatut = [
            'en_attente' => [],
            'paye' => [],
            'rembourse' => [],
            'annule' => []
        ];

        foreach ($paiements as $paiement) {
            $paiementsParStatut[$paiement['statut']][] = $paiement;
        }

        $data = [
            'title' => 'Gestion des Paiements',
            'page_title' => 'Paiements',
            'breadcrumbs' => '<li class="breadcrumb-item">Gestion</li><li class="breadcrumb-item active">Paiements</li>',
            'paiements' => $paiementsParStatut
        ];

        return view('admin/pages/paiements/index', $data);
    }

    public function updateStatut($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Paiement non trouvé.'
            ]);
        }

        $paiement = $this->paiementModel->find($id);
        if (!$paiement) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Paiement non trouvé.'
            ]);
        }

        $nouveauStatut = $this->request->getPost('statut');
        $statutsValides = ['en_attente', 'paye', 'rembourse', 'annule'];

        if (!in_array($nouveauStatut, $statutsValides)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Statut invalide.'
            ]);
        }

        if ($this->paiementModel->update($id, ['statut' => $nouveauStatut])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Statut du paiement mis à jour avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut.'
            ]);
        }
    }

    public function genererFacture($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Paiement non trouvé.'
            ]);
        }

        $facture = $this->paiementModel->genererFacture($id);

        if (!$facture) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Paiement non trouvé.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'facture' => $facture
        ]);
    }

    public function get($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Paiement non trouvé.'
            ]);
        }

        $paiement = $this->paiementModel
            ->select('paiements.*, locataires.nom as locataire_nom, locataires.email, reservations.date_debut, reservations.date_fin, appartements.adresse')
            ->join('locataires', 'locataires.id = paiements.locataire_id')
            ->join('reservations', 'reservations.id = paiements.reservation_id')
            ->join('appartements', 'appartements.id = reservations.appartement_id')
            ->find($id);

        if (!$paiement) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Paiement non trouvé.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'paiement' => $paiement
        ]);
    }
}
