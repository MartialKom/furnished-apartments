<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LocataireModel;
use App\Models\ReservationModel;
use App\Models\AppartementModel;
use CodeIgniter\HTTP\ResponseInterface;

class ReservationController extends BaseController
{
    protected $locataireModel;
    protected $reservationModel;
    protected $appartementModel;

    public function __construct()
    {
        $this->locataireModel = new LocataireModel();
        $this->reservationModel = new ReservationModel();
        $this->appartementModel = new AppartementModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Réservation - T-Lodge',
            'description' => 'Réservez votre appartement meublé',
            'appartements' => $this->appartementModel->where('statut', 'disponible')->findAll()
        ];

        return view('frontend/reservation/form', $data);
    }

    public function create()
    {
        $rules = [
            'nom' => 'required|string|max_length[100]',
            'email' => 'required|valid_email',
            'telephone' => 'permit_empty|string|max_length[20]',
            'appartement_id' => 'required|integer',
            'dateDebut' => 'required|valid_date',
            'dateFin' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            log_message('error', 'Validation échouée pour la création de réservation: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();

        // Vérifier la disponibilité de l'appartement
        $disponible = $this->reservationModel->verifierDisponibilite(
            $data['appartement_id'],
            $data['dateDebut'],
            $data['dateFin']
        );

        if (!$disponible) {
            log_message('warning', 'Appartement ' . $data['appartement_id'] . ' non disponible du ' . $data['dateDebut'] . ' au ' . $data['dateFin']);
            return redirect()->back()->withInput()->with('error', 'L\'appartement n\'est pas disponible pour ces dates.');
        }

        // Calculer le prix total
        $prixCalcule = $this->reservationModel->calculerPrixTotal(
            $data['appartement_id'],
            $data['dateDebut'],
            $data['dateFin'],
            0 // Pas de réduction pour réservations en ligne
        );

        if (!$prixCalcule) {
            log_message('error', 'Impossible de calculer le prix pour l\'appartement ' . $data['appartement_id']);
            return redirect()->back()->withInput()->with('error', 'Erreur lors du calcul du prix.');
        }

        // Créer la réservation
        $reservationData = [
            'date_debut' => $data['dateDebut'],
            'date_fin' => $data['dateFin'],
            'locataire_id' => null, // Pas de locataire pour réservations en ligne
            'appartement_id' => $data['appartement_id'],
            'statut' => 'en_attente',
            'type_reservation' => 'en_ligne',
            'montant_total' => $prixCalcule['montant_total'],
            'montant_paye' => 0.00,
            'montant_restant' => $prixCalcule['montant_total'],
            'prix_original' => $prixCalcule['prix_original'],
            'reduction_pourcentage' => 0,
            'montant_reduction' => 0,
            // Informations du client
            'client_nom' => $data['nom'],
            'client_email' => $data['email'],
            'client_telephone' => $data['telephone'] ?? null,
            'notes' => $data['notes'] ?? null
        ];

        log_message('info', 'Tentative de création de réservation: ' . json_encode($reservationData));

        $reservationId = $this->reservationModel->insert($reservationData);

        if ($reservationId) {
            log_message('info', 'Réservation créée avec succès, ID: ' . $reservationId);

            // Récupérer les données complètes de la réservation et de l'appartement
            $reservation = $this->reservationModel->find($reservationId);
            $appartement = $this->appartementModel->find($data['appartement_id']);

            // Envoyer les emails de notification
            $notificationService = new \App\Libraries\NotificationService();

            // Email au client
            try {
                $notificationService->envoyerConfirmationReservation($reservation, $appartement);
                log_message('info', 'Email de confirmation envoyé au client: ' . $reservation['client_email']);
            } catch (\Exception $e) {
                log_message('error', 'Erreur envoi email client: ' . $e->getMessage());
            }

            // Email au gestionnaire
            try {
                $notificationService->envoyerNotificationNouvelleReservation($reservation, $appartement);
                log_message('info', 'Email de notification envoyé au gestionnaire');
            } catch (\Exception $e) {
                log_message('error', 'Erreur envoi email gestionnaire: ' . $e->getMessage());
            }

            return redirect()->to('/reservation')->with('success', 'Votre réservation a été créée avec succès ! Vous recevrez une confirmation par email.');
        } else {
            $errors = $this->reservationModel->errors();
            log_message('error', 'Erreur lors de l\'insertion de la réservation: ' . json_encode($errors));
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la création de la réservation: ' . implode(', ', $errors));
        }
    }

    public function checkAvailability()
    {
        $appartementId = $this->request->getGet('appartement_id');
        $dateDebut = $this->request->getGet('date_debut');
        $dateFin = $this->request->getGet('date_fin');

        if (!$appartementId || !$dateDebut || !$dateFin) {
            return $this->response->setJSON(['error' => 'Paramètres manquants']);
        }

        $disponible = $this->reservationModel->verifierDisponibilite($appartementId, $dateDebut, $dateFin);

        return $this->response->setJSON(['disponible' => $disponible]);
    }
}