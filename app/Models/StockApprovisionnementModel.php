<?php

namespace App\Models;

use CodeIgniter\Model;

class StockApprovisionnementModel extends Model
{
    protected $table            = 'stock_approvisionnements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'produit_id',
        'quantite',
        'prix_unitaire',
        'prix_total',
        'fournisseur',
        'reference_facture',
        'date_approvisionnement',
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
        'produit_id'             => 'required|integer',
        'quantite'               => 'required|decimal|greater_than[0]',
        'prix_unitaire'          => 'required|decimal|greater_than_equal_to[0]',
        'date_approvisionnement' => 'required|valid_date',
        'utilisateur_id'         => 'required|integer',
    ];

    protected $validationMessages = [
        'quantite' => [
            'required'     => 'La quantité est requise',
            'greater_than' => 'La quantité doit être supérieure à 0',
        ],
        'prix_unitaire' => [
            'required' => 'Le prix unitaire est requis',
        ],
        'date_approvisionnement' => [
            'required'   => 'La date d\'approvisionnement est requise',
            'valid_date' => 'La date n\'est pas valide',
        ],
    ];

    // Callbacks
    protected $beforeInsert = ['calculatePrixTotal'];
    protected $beforeUpdate = ['calculatePrixTotal'];

    /**
     * Calcule le prix total avant insertion/mise à jour
     */
    protected function calculatePrixTotal(array $data)
    {
        if (isset($data['data']['quantite']) && isset($data['data']['prix_unitaire'])) {
            $data['data']['prix_total'] = $data['data']['quantite'] * $data['data']['prix_unitaire'];
        }

        return $data;
    }

    /**
     * Récupère tous les approvisionnements avec détails
     */
    public function getApprovisionnementsAvecDetails()
    {
        return $this->select('stock_approvisionnements.*,
                             stock_produits.nom as produit_nom,
                             stock_produits.unite_mesure,
                             stock_categories.nom as categorie_nom,
                             utilisateurs.nom as utilisateur_nom')
            ->join('stock_produits', 'stock_produits.id = stock_approvisionnements.produit_id')
            ->join('stock_categories', 'stock_categories.id = stock_produits.categorie_id')
            ->join('utilisateurs', 'utilisateurs.id = stock_approvisionnements.utilisateur_id')
            ->orderBy('stock_approvisionnements.date_approvisionnement', 'DESC')
            ->findAll();
    }

    /**
     * Récupère les approvisionnements par période
     */
    public function getApprovisionnementsPeriode($dateDebut, $dateFin)
    {
        return $this->select('stock_approvisionnements.*,
                             stock_produits.nom as produit_nom,
                             stock_produits.unite_mesure,
                             stock_categories.nom as categorie_nom,
                             utilisateurs.nom as utilisateur_nom')
            ->join('stock_produits', 'stock_produits.id = stock_approvisionnements.produit_id')
            ->join('stock_categories', 'stock_categories.id = stock_produits.categorie_id')
            ->join('utilisateurs', 'utilisateurs.id = stock_approvisionnements.utilisateur_id')
            ->where('stock_approvisionnements.date_approvisionnement >=', $dateDebut)
            ->where('stock_approvisionnements.date_approvisionnement <=', $dateFin)
            ->orderBy('stock_approvisionnements.date_approvisionnement', 'DESC')
            ->findAll();
    }

    /**
     * Récupère les approvisionnements d'un produit
     */
    public function getApprovisionnementsProduit($produitId)
    {
        return $this->select('stock_approvisionnements.*, utilisateurs.nom as utilisateur_nom')
            ->join('utilisateurs', 'utilisateurs.id = stock_approvisionnements.utilisateur_id')
            ->where('stock_approvisionnements.produit_id', $produitId)
            ->orderBy('stock_approvisionnements.date_approvisionnement', 'DESC')
            ->findAll();
    }

    /**
     * Statistiques des approvisionnements
     */
    public function getStatistiques($dateDebut = null, $dateFin = null)
    {
        $builder = $this->builder();

        if ($dateDebut && $dateFin) {
            $builder->where('date_approvisionnement >=', $dateDebut)
                    ->where('date_approvisionnement <=', $dateFin);
        }

        $total = $builder->countAllResults(false);

        $result = $builder->select('SUM(prix_total) as montant_total')->get()->getRow();
        $montant = $result ? ($result->montant_total ?? 0) : 0;

        return [
            'total_approvisionnements' => $total,
            'montant_total'            => $montant,
        ];
    }
}
