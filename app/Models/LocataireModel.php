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
    protected $allowedFields    = [
        'nom', 
        'email', 
        'telephone', 
        'historiqueReservations',
        'date_naissance',
        'type_piece',
        'numero_piece',
        'lieu_naissance',
        'nationalite'
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
        'nom' => 'required|string|max_length[100]',
        'email' => 'required|valid_email',
        'telephone' => 'permit_empty|string|max_length[20]',
        'date_naissance' => 'permit_empty|valid_date',
        'type_piece' => 'required|in_list[CNI,PASSPORT,CARTE_SEJOUR,AUTRE]',
        'numero_piece' => 'permit_empty|string|max_length[50]',
        'lieu_naissance' => 'permit_empty|string|max_length[255]',
        'nationalite' => 'required|string|max_length[100]'
    ];
    protected $validationMessages   = [
        'nom' => [
            'required' => 'Le nom est obligatoire.',
            'max_length' => 'Le nom ne peut pas dépasser 100 caractères.'
        ],
        'email' => [
            'required' => 'L\'email est obligatoire.',
            'valid_email' => 'L\'email doit être valide.'
        ],
        'telephone' => [
            'max_length' => 'Le téléphone ne peut pas dépasser 20 caractères.'
        ],
        'date_naissance' => [
            'valid_date' => 'La date de naissance doit être valide.'
        ],
        'type_piece' => [
            'required' => 'Le type de pièce d\'identité est obligatoire.',
            'in_list' => 'Le type de pièce d\'identité doit être CNI, PASSPORT, CARTE_SEJOUR ou AUTRE.'
        ],
        'numero_piece' => [
            'max_length' => 'Le numéro de pièce ne peut pas dépasser 50 caractères.'
        ],
        'lieu_naissance' => [
            'max_length' => 'Le lieu de naissance ne peut pas dépasser 255 caractères.'
        ],
        'nationalite' => [
            'required' => 'La nationalité est obligatoire.',
            'max_length' => 'La nationalité ne peut pas dépasser 100 caractères.'
        ]
    ];
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
