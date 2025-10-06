<?php

namespace App\Models;

use CodeIgniter\Model;

class StockSortieModel extends Model
{
    protected $table            = 'stock_sorties';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'produit_id',
        'quantite',
        'appartement_id',
        'destination',
        'motif',
        'date_sortie',
        'notes',
        'utilisateur_id',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'produit_id'     => 'required|integer',
        'quantite'       => 'required|decimal|greater_than[0]',
        'date_sortie'    => 'required|valid_date',
        'utilisateur_id' => 'required|integer',
    ];

    protected $validationMessages = [
        'quantite' => [
            'required'     => 'La quantité est requise',
            'greater_than' => 'La quantité doit être supérieure à 0',
        ],
        'date_sortie' => [
            'required'   => 'La date de sortie est requise',
            'valid_date' => 'La date n\'est pas valide',
        ],
    ];

    /**
     * Récupère toutes les sorties avec détails
     */
    public function getSortiesAvecDetails()
    {
        return $this->select('stock_sorties.*,
                             stock_produits.nom as produit_nom,
                             stock_produits.unite_mesure,
                             stock_categories.nom as categorie_nom,
                             appartements.adresse as appartement_nom,
                             utilisateurs.nom as utilisateur_nom')
            ->join('stock_produits', 'stock_produits.id = stock_sorties.produit_id')
            ->join('stock_categories', 'stock_categories.id = stock_produits.categorie_id')
            ->join('appartements', 'appartements.id = stock_sorties.appartement_id', 'left')
            ->join('utilisateurs', 'utilisateurs.id = stock_sorties.utilisateur_id')
            ->orderBy('stock_sorties.date_sortie', 'DESC')
            ->findAll();
    }

    /**
     * Récupère les sorties par période
     */
    public function getSortiesPeriode($dateDebut, $dateFin)
    {
        return $this->select('stock_sorties.*,
                             stock_produits.nom as produit_nom,
                             stock_produits.unite_mesure,
                             stock_produits.prix_moyen,
                             stock_categories.nom as categorie_nom,
                             appartements.adresse as appartement_nom,
                             utilisateurs.nom as utilisateur_nom')
            ->join('stock_produits', 'stock_produits.id = stock_sorties.produit_id')
            ->join('stock_categories', 'stock_categories.id = stock_produits.categorie_id')
            ->join('appartements', 'appartements.id = stock_sorties.appartement_id', 'left')
            ->join('utilisateurs', 'utilisateurs.id = stock_sorties.utilisateur_id')
            ->where('stock_sorties.date_sortie >=', $dateDebut)
            ->where('stock_sorties.date_sortie <=', $dateFin)
            ->orderBy('stock_sorties.date_sortie', 'DESC')
            ->findAll();
    }

    /**
     * Récupère les sorties d'un produit
     */
    public function getSortiesProduit($produitId)
    {
        return $this->select('stock_sorties.*,
                             appartements.nom as appartement_nom,
                             utilisateurs.nom as utilisateur_nom')
            ->join('appartements', 'appartements.id = stock_sorties.appartement_id', 'left')
            ->join('utilisateurs', 'utilisateurs.id = stock_sorties.utilisateur_id')
            ->where('stock_sorties.produit_id', $produitId)
            ->orderBy('stock_sorties.date_sortie', 'DESC')
            ->findAll();
    }

    /**
     * Récupère les sorties d'un appartement
     */
    public function getSortiesAppartement($appartementId)
    {
        return $this->select('stock_sorties.*,
                             stock_produits.nom as produit_nom,
                             stock_produits.unite_mesure,
                             utilisateurs.nom as utilisateur_nom,
                             appartements.adresse as appartement_nom')
            ->join('stock_produits', 'stock_produits.id = stock_sorties.produit_id')
            ->join('utilisateurs', 'utilisateurs.id = stock_sorties.utilisateur_id')
            ->join('appartements', 'appartements.id = stock_sorties.appartement_id', 'left')
            ->where('stock_sorties.appartement_id', $appartementId)
            ->orderBy('stock_sorties.date_sortie', 'DESC')
            ->findAll();
    }

    /**
     * Statistiques des sorties
     */
    public function getStatistiques($dateDebut = null, $dateFin = null)
    {
        $builder = $this->builder();

        if ($dateDebut && $dateFin) {
            $builder->where('date_sortie >=', $dateDebut)
                    ->where('date_sortie <=', $dateFin);
        }

        $totalSorties = $builder->countAllResults(false);

        // Valeur des sorties (basée sur le prix moyen)
        $valeurBuilder = $this->select('SUM(stock_sorties.quantite * stock_produits.prix_moyen) as valeur_totale')
            ->join('stock_produits', 'stock_produits.id = stock_sorties.produit_id');

        if ($dateDebut && $dateFin) {
            $valeurBuilder->where('stock_sorties.date_sortie >=', $dateDebut)
                          ->where('stock_sorties.date_sortie <=', $dateFin);
        }

        $valeur = $valeurBuilder->first();

        return [
            'total_sorties'  => $totalSorties,
            'valeur_totale'  => $valeur['valeur_totale'] ?? 0,
        ];
    }
}
