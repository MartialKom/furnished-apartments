<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table            = 'reservations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['date_debut', 'date_fin', 'locataire_id', 'appartement_id', 'statut', 'montant_total', 'motif_annulation'];

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
        'date_debut' => 'required|valid_date',
        'date_fin' => 'required|valid_date',
        'locataire_id' => 'required|integer',
        'appartement_id' => 'required|integer',
        'statut' => 'required|in_list[en_attente,confirmee,annulee,terminee]',
        'montant_total' => 'required|decimal',
        'motif_annulation' => 'permit_empty|string'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function creerReservation($data)
    {
        return $this->insert($data);
    }

    public function annulerReservation($id)
    {
        return $this->update($id, ['statut' => 'annulee']);
    }

    public function verifierDisponibilite($appartementId, $dateDebut, $dateFin)
    {
        $conflits = $this->where('appartement_id', $appartementId)
                         ->where('statut !=', 'annulee')
                         ->groupStart()
                             ->where('date_debut <=', $dateFin)
                             ->where('date_fin >=', $dateDebut)
                         ->groupEnd()
                         ->findAll();
        
        return empty($conflits);
    }

    public function getReservationsWithDetails()
    {
        return $this->select('reservations.*, locataires.nom as locataire_nom, locataires.email, appartements.adresse')
                    ->join('locataires', 'locataires.id = reservations.locataire_id')
                    ->join('appartements', 'appartements.id = reservations.appartement_id')
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
