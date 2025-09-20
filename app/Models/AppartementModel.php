<?php

namespace App\Models;

use CodeIgniter\Model;

class AppartementModel extends Model
{
    protected $table            = 'appartements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['adresse', 'photos', 'equipements', 'tarifs', 'statut', 'type'];

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
        'adresse' => 'required|string|max_length[255]',
        'tarifs' => 'required|decimal',
        'statut' => 'required|in_list[disponible,occupe,maintenance]',
        'type' => 'required|in_list[meuble,non_meuble]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function creerFiche($data)
    {
        return $this->insert($data);
    }

    public function mettreAJourStatut($id, $statut)
    {
        return $this->update($id, ['statut' => $statut]);
    }

    public function obtenirDisponibles()
    {
        return $this->where('statut', 'disponible')->findAll();
    }

    public function obtenirParType($type)
    {
        return $this->where('type', $type)->where('statut', 'disponible')->findAll();
    }

    public function obtenirMeubles()
    {
        return $this->obtenirParType('meuble');
    }

    public function obtenirNonMeubles()
    {
        return $this->obtenirParType('non_meuble');
    }

    public function changerType($id, $nouveauType)
    {
        return $this->update($id, ['type' => $nouveauType]);
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
