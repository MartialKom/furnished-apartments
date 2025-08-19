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
            'title' => 'Réservation - Furnished Apartments',
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
            return redirect()->back()->withInput()->with('error', 'L\'appartement n\'est pas disponible pour ces dates.');
        }

        // Vérifier si le locataire existe déjà
        $locataire = $this->locataireModel->where('email', $data['email'])->first();
        
        if (!$locataire) {
            // Créer un nouveau locataire
            $locataireData = [
                'nom' => $data['nom'],
                'email' => $data['email'],
                'telephone' => $data['telephone'] ?? ''
            ];
            
            $locataireId = $this->locataireModel->insert($locataireData);
            if (!$locataireId) {
                return redirect()->back()->withInput()->with('error', 'Erreur lors de la création du profil locataire.');
            }
        } else {
            $locataireId = $locataire['id'];
        }

        // Créer la réservation
        $reservationData = [
            'dateDebut' => $data['dateDebut'],
            'dateFin' => $data['dateFin'],
            'locataire_id' => $locataireId,
            'appartement_id' => $data['appartement_id'],
            'statut' => 'en_attente'
        ];

        $reservationId = $this->reservationModel->insert($reservationData);

        if ($reservationId) {
            return redirect()->to('/reservation')->with('success', 'Votre réservation a été créée avec succès ! Vous recevrez une confirmation par email.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la création de la réservation.');
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