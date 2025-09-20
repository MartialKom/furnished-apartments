<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReservationModel;
use App\Models\LocataireModel;
use App\Models\AppartementModel;
use App\Models\PaiementModel;
use CodeIgniter\HTTP\ResponseInterface;

class ReservationController extends BaseController
{
    protected $reservationModel;
    protected $locataireModel;
    protected $appartementModel;
    protected $paiementModel;

    public function __construct()
    {
        $this->reservationModel = new ReservationModel();
        $this->locataireModel = new LocataireModel();
        $this->appartementModel = new AppartementModel();
        $this->paiementModel = new PaiementModel();
    }

    public function index()
    {
        // Récupérer toutes les réservations avec les informations du client et de l'appartement
        $reservations = $this->reservationModel->getReservationsWithClientInfo();

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

    public function create()
    {
        $rules = [
            'appartement_id' => 'required|integer',
            'date_debut' => 'required|valid_date',
            'date_fin' => 'required|valid_date',
            'montant_paye' => 'permit_empty|decimal',
            'reduction_pourcentage' => 'permit_empty|decimal',
            'type_reservation' => 'required|in_list[en_ligne,telephonique]',
            'notes' => 'permit_empty|string'
        ];

        // Si c'est un nouveau client, ajouter les règles de validation
        $clientType = $this->request->getPost('client_type');
        
        if ($clientType === 'nouveau') {
            $rules['client_nom'] = 'required|string|max_length[255]';
            $rules['client_email'] = 'required|valid_email';
            $rules['client_telephone'] = 'required|string|max_length[50]';
        } else {
            $rules['locataire_id'] = 'required|integer';
        }

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Vérifier la disponibilité de l'appartement
        $dateDebut = $this->request->getPost('date_debut');
        $dateFin = $this->request->getPost('date_fin');
        $appartementId = $this->request->getPost('appartement_id');

        if (!$this->reservationModel->verifierDisponibilite($appartementId, $dateDebut, $dateFin)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'L\'appartement n\'est pas disponible pour ces dates.'
            ]);
        }

        // Gérer la création ou sélection du locataire
        $locataireId = null;
        if ($clientType === 'nouveau') {
            // Créer un nouveau locataire
            $locataireData = [
                'nom' => $this->request->getPost('client_nom'),
                'email' => $this->request->getPost('client_email'),
                'telephone' => $this->request->getPost('client_telephone')
            ];
            
            // Vérifier si l'email existe déjà
            $existingLocataire = $this->locataireModel->where('email', $locataireData['email'])->first();
            if ($existingLocataire) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Un locataire avec cet email existe déjà. Veuillez sélectionner "Client existant" ou utiliser un autre email.'
                ]);
            }
            
            $locataireId = $this->locataireModel->insert($locataireData);
            if (!$locataireId) {
                $errors = $this->locataireModel->errors();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de la création du locataire.',
                    'errors' => $errors
                ]);
            }
        } else {
            $locataireId = $this->request->getPost('locataire_id');
        }

        // Calculer le prix total avec réduction
        $reductionPourcentage = $this->request->getPost('reduction_pourcentage') ?? 0;
        $prixCalcul = $this->reservationModel->calculerPrixTotal($appartementId, $dateDebut, $dateFin, $reductionPourcentage);
        
        if (!$prixCalcul) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors du calcul du prix.'
            ]);
        }

        $data = [
            'locataire_id' => $locataireId,
            'appartement_id' => $appartementId,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'montant_total' => $prixCalcul['montant_total'],
            'prix_original' => $prixCalcul['prix_original'],
            'montant_reduction' => $prixCalcul['montant_reduction'],
            'reduction_pourcentage' => $reductionPourcentage,
            'montant_paye' => $this->request->getPost('montant_paye') ?? 0,
            'type_reservation' => $this->request->getPost('type_reservation'),
            'notes' => $this->request->getPost('notes'),
            'statut' => 'confirmee' // Les réservations manuelles sont confirmées par défaut
        ];

        $reservationId = $this->reservationModel->creerReservationManuelle($data);

        if ($reservationId) {
            // Si un montant a été payé, enregistrer le paiement
            $montantPaye = $data['montant_paye'];
            if ($montantPaye > 0) {
                $paiementData = [
                    'reservation_id' => $reservationId,
                    'locataire_id' => $data['locataire_id'],
                    'montant' => $montantPaye,
                    'date' => date('Y-m-d'),
                    'statut' => 'paye'
                ];
                $this->paiementModel->enregistrerPaiementPartiel($paiementData);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Réservation créée avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la création de la réservation.'
            ]);
        }
    }

    public function addPayment($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Réservation non trouvée.'
            ]);
        }

        $rules = [
            'montant' => 'required|decimal|greater_than[0]',
            'date' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $reservation = $this->reservationModel->find($id);
        if (!$reservation) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Réservation non trouvée.'
            ]);
        }

        $montantPaiement = $this->request->getPost('montant');
        
        // Vérifier que le paiement ne dépasse pas le montant restant
        if ($montantPaiement > $reservation['montant_restant']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Le montant du paiement ne peut pas dépasser le montant restant.'
            ]);
        }

        $paiementData = [
            'reservation_id' => $id,
            'locataire_id' => $reservation['locataire_id'],
            'montant' => $montantPaiement,
            'date' => $this->request->getPost('date'),
            'statut' => 'paye'
        ];

        if ($this->paiementModel->enregistrerPaiementPartiel($paiementData)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Paiement enregistré avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement du paiement.'
            ]);
        }
    }

    public function getPayments($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Réservation non trouvée.'
            ]);
        }

        $paiements = $this->paiementModel->getPaiementsByReservation($id);
        
        return $this->response->setJSON([
            'success' => true,
            'paiements' => $paiements
        ]);
    }

    public function calculatePrice()
    {
        $appartementId = $this->request->getPost('appartement_id');
        $dateDebut = $this->request->getPost('date_debut');
        $dateFin = $this->request->getPost('date_fin');
        $reductionPourcentage = $this->request->getPost('reduction_pourcentage') ?? 0;

        if (!$appartementId || !$dateDebut || !$dateFin) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Données manquantes pour le calcul.'
            ]);
        }

        $prixCalcul = $this->reservationModel->calculerPrixTotal($appartementId, $dateDebut, $dateFin, $reductionPourcentage);
        
        if (!$prixCalcul) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors du calcul du prix.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'prix' => $prixCalcul
        ]);
    }

}