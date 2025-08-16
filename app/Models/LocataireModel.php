<?php

namespace App\Models;

use CodeIgniter\Model;

class LocataireModel extends Model
{
    protected $table            = 'locataires';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nom', 'email', 'telephone', 'historiqueReservations'];

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
        'nom' => 'required|string|max_length[100]',
        'email' => 'required|valid_email|is_unique[locataires.email,id,{id}]',
        'telephone' => 'permit_empty|string|max_length[20]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function sInscrire($data)
    {
        return $this->insert($data);
    }

    public function consulterReservations($locataireId)
    {
        $reservationModel = new \App\Models\ReservationModel();
        return $reservationModel->where('locataire_id', $locataireId)->findAll();
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
