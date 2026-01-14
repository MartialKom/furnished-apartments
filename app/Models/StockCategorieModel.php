<?php

namespace App\Models;

use CodeIgniter\Model;

class StockCategorieModel extends Model
{
    protected $table            = 'stock_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nom',
        'description',
        'actif',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'nom' => 'required|min_length[3]|max_length[100]',
    ];

    protected $validationMessages = [
        'nom' => [
            'required'   => 'Le nom de la catégorie est requis',
            'min_length' => 'Le nom doit contenir au moins 3 caractères',
            'max_length' => 'Le nom ne peut pas dépasser 100 caractères',
        ],
    ];

    /**
     * Récupère les catégories actives
     */
    public function getCategoriesActives()
    {
        return $this->where('actif', 1)->findAll();
    }

    /**
     * Récupère une catégorie avec le nombre de produits
     */
    public function getCategorieAvecProduits($id)
    {
        return $this->select('stock_categories.*, COUNT(stock_produits.id) as nb_produits')
            ->join('stock_produits', 'stock_produits.categorie_id = stock_categories.id', 'left')
            ->where('stock_categories.id', $id)
            ->groupBy('stock_categories.id')
            ->first();
    }

    /**
     * Récupère toutes les catégories avec le nombre de produits
     */
    public function getAllCategoriesAvecProduits()
    {
        return $this->select('stock_categories.*, COUNT(stock_produits.id) as nb_produits')
            ->join('stock_produits', 'stock_produits.categorie_id = stock_categories.id', 'left')
            ->groupBy('stock_categories.id')
            ->orderBy('stock_categories.nom', 'ASC')
            ->findAll();
    }
}
