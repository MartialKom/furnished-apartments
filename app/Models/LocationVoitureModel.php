<?php

namespace App\Models;

use CodeIgniter\Model;

class LocationVoitureModel extends Model
{
    protected $table            = 'locations_voitures';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'voiture_id',
        'locataire_id',
        'client_nom',
        'client_email',
        'client_telephone',
        'client_permis',
        'date_debut',
        'date_fin_prevue',
        'date_fin_reelle',
        'nombre_jours',
        'tarif_journalier',
        'montant_total',
        'caution_versee',
        'caution_restituee',
        'montant_paye',
        'montant_restant',
        'kilometrage_depart',
        'kilometrage_retour',
        'etat_depart',
        'etat_retour',
        'statut',
        'mode_paiement',
        'notes',
        'enregistre_par'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'voiture_id' => 'required|integer',
        'date_debut' => 'required|valid_date',
        'date_fin_prevue' => 'required|valid_date',
        'nombre_jours' => 'required|integer|greater_than[0]',
        'tarif_journalier' => 'required|decimal|greater_than[0]',
        'montant_total' => 'required|decimal|greater_than[0]',
        'caution_versee' => 'permit_empty|decimal',
        'montant_paye' => 'permit_empty|decimal',
        'client_permis' => 'permit_empty|string|max_length[100]',
        'statut' => 'required|in_list[en_attente,en_cours,terminee,annulee]'
    ];

    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Créer une location
     */
    public function creerLocation($data)
    {
        log_message('info', '[LocationVoitureModel] Création location: ' . json_encode($data));

        // Calculer montant_restant automatiquement
        if (!isset($data['montant_restant'])) {
            $data['montant_restant'] = $data['montant_total'] - ($data['montant_paye'] ?? 0);
        }

        // Calculer nombre de jours automatiquement si non fourni
        if (!isset($data['nombre_jours']) && isset($data['date_debut']) && isset($data['date_fin_prevue'])) {
            $debut = new \DateTime($data['date_debut']);
            $fin = new \DateTime($data['date_fin_prevue']);
            $data['nombre_jours'] = $debut->diff($fin)->days + 1; // +1 pour inclure le jour de départ
        }

        // Calculer montant_total si non fourni
        if (!isset($data['montant_total']) && isset($data['tarif_journalier']) && isset($data['nombre_jours'])) {
            $data['montant_total'] = $data['tarif_journalier'] * $data['nombre_jours'];
        }

        $result = $this->insert($data);

        if ($result) {
            // Marquer la voiture comme louée
            $voitureModel = new \App\Models\VoitureModel();
            $voitureModel->marquerCommeLouee($data['voiture_id']);

            log_message('info', "[LocationVoitureModel] Location créée - ID: {$result}");
        }

        return $result;
    }

    /**
     * Récupérer les locations avec détails
     */
    public function getLocationsWithDetails()
    {
        return $this->select('locations_voitures.*,
                              COALESCE(locataires.nom, locations_voitures.client_nom) as nom_client,
                              COALESCE(locataires.email, locations_voitures.client_email) as email_client,
                              COALESCE(locataires.telephone, locations_voitures.client_telephone) as telephone_client,
                              voitures.marque as voiture_marque,
                              voitures.modele as voiture_modele,
                              voitures.immatriculation,
                              voitures.tarif_journalier as tarif_voiture,
                              voitures.photos')
                    ->join('voitures', 'voitures.id = locations_voitures.voiture_id')
                    ->join('locataires', 'locataires.id = locations_voitures.locataire_id', 'left')
                    ->orderBy('locations_voitures.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Récupérer les locations par statut
     */
    public function getByStatut($statut)
    {
        return $this->select('locations_voitures.*,
                              COALESCE(locataires.nom, locations_voitures.client_nom) as nom_client,
                              voitures.marque,
                              voitures.modele,
                              voitures.immatriculation')
                    ->join('voitures', 'voitures.id = locations_voitures.voiture_id')
                    ->join('locataires', 'locataires.id = locations_voitures.locataire_id', 'left')
                    ->where('locations_voitures.statut', $statut)
                    ->orderBy('locations_voitures.date_debut', 'DESC')
                    ->findAll();
    }

    /**
     * Démarrer une location (passage de en_attente à en_cours)
     */
    public function demarrerLocation($id, $data = [])
    {
        log_message('info', "[LocationVoitureModel] Démarrage location ID: {$id}");

        $updateData = [
            'statut' => 'en_cours',
            'kilometrage_depart' => $data['kilometrage_depart'] ?? null,
            'etat_depart' => $data['etat_depart'] ?? null
        ];

        return $this->update($id, $updateData);
    }

    /**
     * Terminer une location (retour du véhicule)
     */
    public function terminerLocation($id, $data)
    {
        log_message('info', "[LocationVoitureModel] Fin location ID: {$id}");

        $location = $this->find($id);
        if (!$location) {
            return false;
        }

        $updateData = [
            'statut' => 'terminee',
            'date_fin_reelle' => $data['date_fin_reelle'] ?? date('Y-m-d'),
            'kilometrage_retour' => $data['kilometrage_retour'] ?? null,
            'etat_retour' => $data['etat_retour'] ?? null,
            'caution_restituee' => $data['caution_restituee'] ?? false
        ];

        $result = $this->update($id, $updateData);

        if ($result) {
            // Marquer la voiture comme disponible
            $voitureModel = new \App\Models\VoitureModel();
            $voitureModel->marquerCommeDisponible($location['voiture_id']);

            // Mettre à jour le kilométrage de la voiture
            if (!empty($data['kilometrage_retour'])) {
                $voitureModel->updateKilometrage($location['voiture_id'], $data['kilometrage_retour']);
            }
        }

        return $result;
    }

    /**
     * Annuler une location
     */
    public function annulerLocation($id, $motif = null)
    {
        log_message('info', "[LocationVoitureModel] Annulation location ID: {$id}");

        $location = $this->find($id);
        if (!$location) {
            return false;
        }

        $updateData = [
            'statut' => 'annulee',
            'notes' => ($location['notes'] ?? '') . "\nAnnulée: " . $motif
        ];

        $result = $this->update($id, $updateData);

        if ($result && $location['statut'] !== 'terminee') {
            // Marquer la voiture comme disponible
            $voitureModel = new \App\Models\VoitureModel();
            $voitureModel->marquerCommeDisponible($location['voiture_id']);
        }

        return $result;
    }

    /**
     * Enregistrer un paiement
     */
    public function enregistrerPaiement($id, $montant, $mode_paiement = null)
    {
        $location = $this->find($id);
        if (!$location) {
            return false;
        }

        $montantPaye = ($location['montant_paye'] ?? 0) + $montant;
        $montantRestant = $location['montant_total'] - $montantPaye;

        return $this->update($id, [
            'montant_paye' => $montantPaye,
            'montant_restant' => $montantRestant,
            'mode_paiement' => $mode_paiement ?? $location['mode_paiement']
        ]);
    }

    /**
     * Récupérer les locations en cours
     */
    public function getLocationsEnCours()
    {
        return $this->getByStatut('en_cours');
    }

    /**
     * Récupérer les locations en retard (date_fin_prevue dépassée et toujours en_cours)
     */
    public function getLocationsEnRetard()
    {
        $today = date('Y-m-d');

        return $this->select('locations_voitures.*,
                              COALESCE(locataires.nom, locations_voitures.client_nom) as nom_client,
                              COALESCE(locataires.telephone, locations_voitures.client_telephone) as telephone_client,
                              voitures.marque,
                              voitures.modele,
                              voitures.immatriculation')
                    ->join('voitures', 'voitures.id = locations_voitures.voiture_id')
                    ->join('locataires', 'locataires.id = locations_voitures.locataire_id', 'left')
                    ->where('locations_voitures.statut', 'en_cours')
                    ->where('locations_voitures.date_fin_prevue <', $today)
                    ->orderBy('locations_voitures.date_fin_prevue', 'ASC')
                    ->findAll();
    }

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
