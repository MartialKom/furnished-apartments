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
    protected $allowedFields    = [
        'adresse',
        'photos',
        'equipements',
        'tarifs',
        'statut',
        'type',
        'categorie',
        'nombre_chambres',
        'superficie',
        'numero_bien',
        'etage',
        'description'
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
        'adresse' => 'required|string|max_length[255]',
        'tarifs' => 'required|decimal',
        'statut' => 'required|in_list[disponible,occupe,maintenance]',
        'type' => 'required|in_list[meuble,non_meuble]',
        'categorie' => 'required|in_list[appartement,logement,boutique]',
        'nombre_chambres' => 'permit_empty|integer|in_list[2,3]',
        'superficie' => 'permit_empty|decimal',
        'numero_bien' => 'permit_empty|string|max_length[50]',
        'etage' => 'permit_empty|integer',
        'description' => 'permit_empty|string'
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

    /**
     * Obtenir les biens par catégorie
     */
    public function obtenirParCategorie($categorie)
    {
        return $this->where('categorie', $categorie)->findAll();
    }

    /**
     * Obtenir les appartements par nombre de chambres
     */
    public function obtenirParNombreChambres($nombreChambres)
    {
        return $this->where('categorie', 'appartement')
                    ->where('nombre_chambres', $nombreChambres)
                    ->where('statut', 'disponible')
                    ->findAll();
    }

    /**
     * Obtenir tous les appartements (2 ou 3 chambres)
     */
    public function obtenirAppartements()
    {
        return $this->where('categorie', 'appartement')->findAll();
    }

    /**
     * Obtenir tous les logements
     */
    public function obtenirLogements()
    {
        return $this->where('categorie', 'logement')->findAll();
    }

    /**
     * Obtenir toutes les boutiques
     */
    public function obtenirBoutiques()
    {
        return $this->where('categorie', 'boutique')->findAll();
    }

    /**
     * Obtenir les biens disponibles par catégorie et type
     */
    public function obtenirDisponiblesParCategorieType($categorie, $type = null)
    {
        $builder = $this->where('categorie', $categorie)
                        ->where('statut', 'disponible');

        if ($type !== null) {
            $builder->where('type', $type);
        }

        return $builder->findAll();
    }

    /**
     * Recherche avancée de biens
     */
    public function rechercherBiens($filters = [])
    {
        $builder = $this->builder();

        if (!empty($filters['categorie'])) {
            $builder->where('categorie', $filters['categorie']);
        }

        if (!empty($filters['type'])) {
            $builder->where('type', $filters['type']);
        }

        if (!empty($filters['nombre_chambres'])) {
            $builder->where('nombre_chambres', $filters['nombre_chambres']);
        }

        if (!empty($filters['statut'])) {
            $builder->where('statut', $filters['statut']);
        }

        if (!empty($filters['tarif_min'])) {
            $builder->where('tarifs >=', $filters['tarif_min']);
        }

        if (!empty($filters['tarif_max'])) {
            $builder->where('tarifs <=', $filters['tarif_max']);
        }

        if (!empty($filters['etage'])) {
            $builder->where('etage', $filters['etage']);
        }

        return $builder->findAll();
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
