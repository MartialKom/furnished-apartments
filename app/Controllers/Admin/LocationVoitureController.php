<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LocationVoitureModel;
use App\Models\VoitureModel;
use App\Models\LocataireModel;
use CodeIgniter\HTTP\ResponseInterface;

class LocationVoitureController extends BaseController
{
    protected $locationModel;
    protected $voitureModel;
    protected $locataireModel;

    public function __construct()
    {
        $this->locationModel = new LocationVoitureModel();
        $this->voitureModel = new VoitureModel();
        $this->locataireModel = new LocataireModel();
    }

    /**
     * Liste toutes les locations
     */
    public function index()
    {
        $locations = $this->locationModel->getLocationsWithDetails();

        // Organiser les locations par statut
        $locationsParStatut = [
            'en_attente' => [],
            'en_cours' => [],
            'terminee' => [],
            'annulee' => []
        ];

        foreach ($locations as $location) {
            $locationsParStatut[$location['statut']][] = $location;
        }

        // Récupérer les voitures disponibles et les locataires pour le formulaire
        $voituresDisponibles = $this->voitureModel->getVoituresDisponibles();
        $locataires = $this->locataireModel->orderBy('nom', 'ASC')->findAll();

        $data = [
            'title' => 'Gestion des Locations de Voitures',
            'page_title' => 'Locations de Voitures',
            'breadcrumbs' => '<li class="breadcrumb-item">Administration</li><li class="breadcrumb-item active">Locations de Voitures</li>',
            'locations' => $locationsParStatut,
            'voitures' => $voituresDisponibles,
            'locataires' => $locataires
        ];

        return view('admin/pages/locations_voitures/index', $data);
    }

    /**
     * Créer une location
     */
    public function store()
    {
        $json = $this->request->getJSON(true);

        log_message('info', '=== DÉBUT CRÉATION LOCATION VOITURE ===');
        log_message('info', 'Données JSON reçues: ' . json_encode($json));

        $rules = [
            'voiture_id' => 'required|integer',
            'type_location' => 'required|in_list[jour,heure]',
            'date_debut' => 'required|valid_date',
            'date_fin_prevue' => 'required|valid_date',
            'client_permis' => 'permit_empty|string|max_length[100]'
        ];

        // Si c'est un nouveau client
        $clientType = $json['client_type'] ?? 'existant';
        if ($clientType === 'nouveau') {
            $rules['client_nom'] = 'required|string|max_length[255]';
            $rules['client_email'] = 'permit_empty|valid_email';
            $rules['client_telephone'] = 'required|string|max_length[50]';
        } else {
            $rules['locataire_id'] = 'required|integer';
        }

        if (!$this->validate($rules, $json)) {
            $errors = $this->validator->getErrors();
            log_message('error', 'VALIDATION ÉCHOUÉE: ' . json_encode($errors));
            return $this->response->setJSON([
                'success' => false,
                'errors' => $errors
            ]);
        }

        // Vérifier disponibilité de la voiture
        $voitureId = $json['voiture_id'];
        $dateDebut = $json['date_debut'];
        $dateFin = $json['date_fin_prevue'];

        if (!$this->voitureModel->estDisponible($voitureId, $dateDebut, $dateFin)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cette voiture n\'est pas disponible pour ces dates.'
            ]);
        }

        // Récupérer le tarif de la voiture
        $voiture = $this->voitureModel->find($voitureId);
        $caution = $voiture['caution'];
        $typeLocation = $json['type_location'] ?? 'jour';

        // Calculer selon le type de location
        $debut = new \DateTime($dateDebut);
        $fin = new \DateTime($dateFin);

        if ($typeLocation === 'heure') {
            // Location horaire
            $tarifHoraire = $voiture['tarif_horaire'] ?? ($voiture['tarif_journalier'] / 24);
            $diff = $debut->diff($fin);
            $dureeHeures = ($diff->days * 24) + $diff->h;

            // Si la durée est inférieure à 1h, compter 1h minimum
            if ($dureeHeures < 1) {
                $dureeHeures = 1;
            }

            $montantTotal = $tarifHoraire * $dureeHeures;

            $data = [
                'voiture_id' => $voitureId,
                'type_location' => 'heure',
                'locataire_id' => $clientType === 'existant' ? ($json['locataire_id'] ?? null) : null,
                'client_nom' => $clientType === 'nouveau' ? ($json['client_nom'] ?? null) : null,
                'client_email' => $clientType === 'nouveau' ? ($json['client_email'] ?? null) : null,
                'client_telephone' => $clientType === 'nouveau' ? ($json['client_telephone'] ?? null) : null,
                'client_permis' => $json['client_permis'] ?? null,
                'date_debut' => $dateDebut,
                'date_fin_prevue' => $dateFin,
                'duree_heures' => $dureeHeures,
                'tarif_horaire' => $tarifHoraire,
                'montant_total' => $montantTotal,
                'caution_versee' => $json['caution_versee'] ?? $caution,
                'montant_paye' => floatval($json['montant_paye'] ?? 0),
                'montant_restant' => $montantTotal - floatval($json['montant_paye'] ?? 0),
                'mode_paiement' => $json['mode_paiement'] ?? null,
                'notes' => $json['notes'] ?? null,
                'kilometrage_depart' => $json['kilometrage_depart'] ?? null,
                'etat_depart' => $json['etat_depart'] ?? 'Bon état',
                'statut' => 'en_cours', // Démarrage automatique
                'enregistre_par' => session()->get('user_id')
            ];
        } else {
            // Location journalière
            $tarifJournalier = $voiture['tarif_journalier'];
            $nombreJours = $debut->diff($fin)->days + 1;
            $montantTotal = $tarifJournalier * $nombreJours;

            $data = [
                'voiture_id' => $voitureId,
                'type_location' => 'jour',
                'locataire_id' => $clientType === 'existant' ? ($json['locataire_id'] ?? null) : null,
                'client_nom' => $clientType === 'nouveau' ? ($json['client_nom'] ?? null) : null,
                'client_email' => $clientType === 'nouveau' ? ($json['client_email'] ?? null) : null,
                'client_telephone' => $clientType === 'nouveau' ? ($json['client_telephone'] ?? null) : null,
                'client_permis' => $json['client_permis'] ?? null,
                'date_debut' => $dateDebut,
                'date_fin_prevue' => $dateFin,
                'nombre_jours' => $nombreJours,
                'tarif_journalier' => $tarifJournalier,
                'montant_total' => $montantTotal,
                'caution_versee' => $json['caution_versee'] ?? $caution,
                'montant_paye' => floatval($json['montant_paye'] ?? 0),
                'montant_restant' => $montantTotal - floatval($json['montant_paye'] ?? 0),
                'mode_paiement' => $json['mode_paiement'] ?? null,
                'notes' => $json['notes'] ?? null,
                'kilometrage_depart' => $json['kilometrage_depart'] ?? null,
                'etat_depart' => $json['etat_depart'] ?? 'Bon état',
                'statut' => 'en_cours', // Démarrage automatique
                'enregistre_par' => session()->get('user_id')
            ];
        }

        $montantPaye = floatval($json['montant_paye'] ?? 0);

        try {
            $locationId = $this->locationModel->creerLocation($data);

            if ($locationId) {
                log_message('info', "Location créée avec succès - ID: {$locationId}");
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Location créée avec succès !'
                ]);
            } else {
                $modelErrors = $this->locationModel->errors();
                log_message('error', 'Échec création location: ' . json_encode($modelErrors));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de la création.',
                    'errors' => $modelErrors
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'EXCEPTION: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Récupérer une location
     */
    public function get($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Location non trouvée.'
            ]);
        }

        $location = $this->locationModel
            ->select('locations_voitures.*,
                      COALESCE(locataires.nom, locations_voitures.client_nom) as nom_client,
                      COALESCE(locataires.email, locations_voitures.client_email) as email_client,
                      COALESCE(locataires.telephone, locations_voitures.client_telephone) as telephone_client,
                      voitures.marque,
                      voitures.modele,
                      voitures.immatriculation,
                      voitures.tarif_journalier as tarif_voiture')
            ->join('voitures', 'voitures.id = locations_voitures.voiture_id')
            ->join('locataires', 'locataires.id = locations_voitures.locataire_id', 'left')
            ->find($id);

        if (!$location) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Location non trouvée.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'location' => $location
        ]);
    }

    /**
     * Démarrer une location
     */
    public function demarrer($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Location non trouvée.'
            ]);
        }

        $json = $this->request->getJSON(true);

        $data = [
            'kilometrage_depart' => $json['kilometrage_depart'] ?? null,
            'etat_depart' => $json['etat_depart'] ?? null
        ];

        if ($this->locationModel->demarrerLocation($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Location démarrée avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors du démarrage de la location.'
            ]);
        }
    }

    /**
     * Terminer une location (retour)
     */
    public function terminer($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Location non trouvée.'
            ]);
        }

        $json = $this->request->getJSON(true);

        $data = [
            'date_fin_reelle' => $json['date_fin_reelle'] ?? date('Y-m-d'),
            'kilometrage_retour' => $json['kilometrage_retour'] ?? null,
            'etat_retour' => $json['etat_retour'] ?? null,
            'caution_restituee' => $json['caution_restituee'] ?? false
        ];

        if ($this->locationModel->terminerLocation($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Retour enregistré avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement du retour.'
            ]);
        }
    }

    /**
     * Annuler une location
     */
    public function annuler($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Location non trouvée.'
            ]);
        }

        $json = $this->request->getJSON(true);
        $motif = $json['motif'] ?? 'Aucun motif fourni';

        if ($this->locationModel->annulerLocation($id, $motif)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Location annulée avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de l\'annulation.'
            ]);
        }
    }

    /**
     * Enregistrer un paiement
     */
    public function enregistrerPaiement($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Location non trouvée.'
            ]);
        }

        $json = $this->request->getJSON(true);

        $montant = $json['montant'] ?? 0;
        $modePaiement = $json['mode_paiement'] ?? null;

        if ($this->locationModel->enregistrerPaiement($id, $montant, $modePaiement)) {
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
}
