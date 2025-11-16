<?php

namespace App\Models;

use CodeIgniter\Model;

class VoitureModel extends Model
{
    protected $table            = 'voitures';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'marque',
        'modele',
        'annee',
        'immatriculation',
        'couleur',
        'nombre_places',
        'type_carburant',
        'transmission',
        'kilometrage',
        'tarif_journalier',
        'caution',
        'statut',
        'photo',
        'numero_chassis',
        'assurance_expire_le',
        'visite_technique_expire_le',
        'notes'
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
        'marque' => 'required|string|max_length[100]',
        'modele' => 'required|string|max_length[100]',
        'annee' => 'required|integer|greater_than[1900]|less_than_equal_to[2030]',
        'immatriculation' => 'required|string|max_length[50]|is_unique[voitures.immatriculation,id,{id}]',
        'couleur' => 'permit_empty|string|max_length[50]',
        'nombre_places' => 'required|integer|greater_than[0]',
        'type_carburant' => 'required|in_list[essence,diesel,electrique,hybride]',
        'transmission' => 'required|in_list[manuelle,automatique]',
        'kilometrage' => 'required|integer|greater_than_equal_to[0]',
        'tarif_journalier' => 'required|decimal|greater_than[0]',
        'caution' => 'permit_empty|decimal|greater_than_equal_to[0]',
        'statut' => 'required|in_list[disponible,louee,maintenance,indisponible]',
        'photo' => 'permit_empty|string|max_length[255]',
        'numero_chassis' => 'permit_empty|string|max_length[100]',
        'assurance_expire_le' => 'permit_empty|valid_date',
        'visite_technique_expire_le' => 'permit_empty|valid_date',
        'notes' => 'permit_empty|string'
    ];

    protected $validationMessages   = [
        'marque' => [
            'required' => 'La marque est obligatoire.'
        ],
        'modele' => [
            'required' => 'Le modèle est obligatoire.'
        ],
        'immatriculation' => [
            'required' => 'L\'immatriculation est obligatoire.',
            'is_unique' => 'Cette immatriculation existe déjà.'
        ],
        'tarif_journalier' => [
            'required' => 'Le tarif journalier est obligatoire.',
            'greater_than' => 'Le tarif doit être supérieur à 0.'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Récupérer toutes les voitures disponibles
     */
    public function getVoituresDisponibles()
    {
        return $this->where('statut', 'disponible')
                    ->orderBy('marque', 'ASC')
                    ->orderBy('modele', 'ASC')
                    ->findAll();
    }

    /**
     * Récupérer les voitures par statut
     */
    public function getByStatut($statut)
    {
        return $this->where('statut', $statut)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Marquer une voiture comme louée
     */
    public function marquerCommeLouee($id)
    {
        return $this->update($id, ['statut' => 'louee']);
    }

    /**
     * Marquer une voiture comme disponible
     */
    public function marquerCommeDisponible($id)
    {
        return $this->update($id, ['statut' => 'disponible']);
    }

    /**
     * Mettre à jour le kilométrage
     */
    public function updateKilometrage($id, $kilometrage)
    {
        return $this->update($id, ['kilometrage' => $kilometrage]);
    }

    /**
     * Vérifier si une voiture est disponible pour des dates données
     */
    public function estDisponible($voitureId, $dateDebut, $dateFin)
    {
        $voiture = $this->find($voitureId);

        if (!$voiture || $voiture['statut'] !== 'disponible') {
            return false;
        }

        // Vérifier s'il n'y a pas de location en conflit
        $locationModel = new \App\Models\LocationVoitureModel();
        $conflits = $locationModel->where('voiture_id', $voitureId)
                                  ->whereIn('statut', ['en_attente', 'en_cours'])
                                  ->groupStart()
                                      ->where('date_debut <=', $dateFin)
                                      ->where('date_fin_prevue >=', $dateDebut)
                                  ->groupEnd()
                                  ->findAll();

        return empty($conflits);
    }

    /**
     * Obtenir les alertes (assurance/visite technique expirées ou proches)
     */
    public function getAlertes()
    {
        $today = date('Y-m-d');
        $dateLimit = date('Y-m-d', strtotime('+30 days')); // 30 jours

        return $this->groupStart()
                        ->where('assurance_expire_le <=', $dateLimit)
                        ->where('assurance_expire_le >=', $today)
                    ->groupEnd()
                    ->orGroupStart()
                        ->where('visite_technique_expire_le <=', $dateLimit)
                        ->where('visite_technique_expire_le >=', $today)
                    ->groupEnd()
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
