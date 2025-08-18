<?php

namespace App\Models;

use CodeIgniter\Model;

class PaiementModel extends Model
{
    protected $table            = 'paiements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['montant', 'date', 'statut', 'locataire_id', 'reservation_id'];

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
        'montant' => 'required|decimal',
        'date' => 'required|valid_date',
        'statut' => 'required|in_list[en_attente,paye,rembourse,annule]',
        'locataire_id' => 'required|integer',
        'reservation_id' => 'required|integer'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function enregistrerPaiement($data)
    {
        return $this->insert($data);
    }

    public function genererFacture($paiementId)
    {
        $paiement = $this->select('paiements.*, locataires.nom as locataire_nom, locataires.email, reservations.dateDebut, reservations.dateFin, appartements.adresse')
                         ->join('locataires', 'locataires.id = paiements.locataire_id')
                         ->join('reservations', 'reservations.id = paiements.reservation_id')
                         ->join('appartements', 'appartements.id = reservations.appartement_id')
                         ->find($paiementId);
        
        return $paiement;
    }

    public function getPaiementsByLocataire($locataireId)
    {
        return $this->where('locataire_id', $locataireId)->findAll();
    }

    public function updateStatut($id, $statut)
    {
        return $this->update($id, ['statut' => $statut]);
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
