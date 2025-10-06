<?php

namespace App\Models;

use CodeIgniter\Model;

class StockProduitModel extends Model
{
    protected $table            = 'stock_produits';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'categorie_id',
        'nom',
        'description',
        'unite_mesure',
        'stock_minimum',
        'stock_actuel',
        'prix_moyen',
        'actif',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'categorie_id'  => 'required|integer',
        'nom'           => 'required|min_length[3]|max_length[150]',
        'unite_mesure'  => 'required|max_length[50]',
        'stock_minimum' => 'permit_empty|decimal',
    ];

    protected $validationMessages = [
        'nom' => [
            'required'   => 'Le nom du produit est requis',
            'min_length' => 'Le nom doit contenir au moins 3 caractères',
        ],
        'categorie_id' => [
            'required' => 'La catégorie est requise',
        ],
        'unite_mesure' => [
            'required' => 'L\'unité de mesure est requise',
        ],
    ];

    /**
     * Récupère tous les produits avec leur catégorie
     */
    public function getProduitsAvecCategorie()
    {
        return $this->select('stock_produits.*, stock_categories.nom as categorie_nom')
            ->join('stock_categories', 'stock_categories.id = stock_produits.categorie_id')
            ->orderBy('stock_categories.nom, stock_produits.nom', 'ASC')
            ->findAll();
    }

    /**
     * Récupère un produit avec sa catégorie
     */
    public function getProduitAvecCategorie($id)
    {
        return $this->select('stock_produits.*, stock_categories.nom as categorie_nom')
            ->join('stock_categories', 'stock_categories.id = stock_produits.categorie_id')
            ->where('stock_produits.id', $id)
            ->first();
    }

    /**
     * Récupère les produits en alerte (stock faible)
     */
    public function getProduitsEnAlerte()
    {
        return $this->select('stock_produits.*, stock_categories.nom as categorie_nom')
            ->join('stock_categories', 'stock_categories.id = stock_produits.categorie_id')
            ->where('stock_produits.stock_actuel <=', 'stock_produits.stock_minimum', false)
            ->where('stock_produits.actif', 1)
            ->orderBy('stock_produits.stock_actuel', 'ASC')
            ->findAll();
    }

    /**
     * Récupère les produits actifs
     */
    public function getProduitsActifs()
    {
        return $this->where('actif', 1)
            ->orderBy('nom', 'ASC')
            ->findAll();
    }

    /**
     * Met à jour le stock et le prix moyen après un approvisionnement
     */
    public function ajouterStock($produitId, $quantite, $prixUnitaire)
    {
        $produit = $this->find($produitId);

        if (!$produit) {
            return false;
        }

        // Calcul du nouveau prix moyen pondéré
        $ancienStock = $produit['stock_actuel'];
        $ancienPrixMoyen = $produit['prix_moyen'];

        $nouveauStock = $ancienStock + $quantite;

        if ($nouveauStock > 0) {
            $nouveauPrixMoyen = (($ancienStock * $ancienPrixMoyen) + ($quantite * $prixUnitaire)) / $nouveauStock;
        } else {
            $nouveauPrixMoyen = $prixUnitaire;
        }

        return $this->update($produitId, [
            'stock_actuel' => $nouveauStock,
            'prix_moyen'   => $nouveauPrixMoyen,
        ]);
    }

    /**
     * Retire du stock
     */
    public function retirerStock($produitId, $quantite)
    {
        $produit = $this->find($produitId);

        if (!$produit) {
            return false;
        }

        $nouveauStock = $produit['stock_actuel'] - $quantite;

        return $this->update($produitId, [
            'stock_actuel' => max(0, $nouveauStock), // Ne pas avoir de stock négatif
        ]);
    }

    /**
     * Ajuste le stock suite à un inventaire
     */
    public function ajusterStock($produitId, $nouveauStock)
    {
        return $this->update($produitId, [
            'stock_actuel' => $nouveauStock,
        ]);
    }

    /**
     * Calcule la valeur totale du stock
     */
    public function getValeurTotaleStock()
    {
        $result = $this->select('SUM(stock_actuel * prix_moyen) as valeur_totale')
            ->where('actif', 1)
            ->first();

        return $result['valeur_totale'] ?? 0;
    }
}
