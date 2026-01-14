<?php

namespace App\Models;

use CodeIgniter\Model;

class StockInventaireDetailModel extends Model
{
    protected $table            = 'stock_inventaire_details';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'inventaire_id',
        'produit_id',
        'stock_theorique',
        'stock_physique',
        'ecart',
        'valeur_ecart',
        'observations',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Callbacks
    protected $beforeInsert = ['calculateEcart'];
    protected $beforeUpdate = ['calculateEcart'];

    /**
     * Calcule l'écart avant insertion/mise à jour
     */
    protected function calculateEcart(array $data)
    {
        if (isset($data['data']['stock_physique']) && isset($data['data']['stock_theorique'])) {
            $data['data']['ecart'] = $data['data']['stock_physique'] - $data['data']['stock_theorique'];

            // Si on a le produit_id, récupérer le prix moyen pour calculer la valeur de l'écart
            if (isset($data['data']['produit_id'])) {
                $produitModel = new StockProduitModel();
                $produit = $produitModel->find($data['data']['produit_id']);

                if ($produit) {
                    $data['data']['valeur_ecart'] = $data['data']['ecart'] * $produit['prix_moyen'];
                }
            }
        }

        return $data;
    }

    /**
     * Récupère les détails d'un inventaire avec infos produits
     */
    public function getDetailsInventaire($inventaireId)
    {
        return $this->select('stock_inventaire_details.*,
                             stock_produits.nom as produit_nom,
                             stock_produits.unite_mesure,
                             stock_produits.prix_moyen,
                             stock_categories.nom as categorie_nom')
            ->join('stock_produits', 'stock_produits.id = stock_inventaire_details.produit_id')
            ->join('stock_categories', 'stock_categories.id = stock_produits.categorie_id')
            ->where('stock_inventaire_details.inventaire_id', $inventaireId)
            ->orderBy('stock_categories.nom, stock_produits.nom', 'ASC')
            ->findAll();
    }

    /**
     * Crée les détails d'inventaire à partir de tous les produits actifs
     */
    public function initialiserInventaire($inventaireId)
    {
        $produitModel = new StockProduitModel();
        $produits = $produitModel->where('actif', 1)->findAll();

        $details = [];

        foreach ($produits as $produit) {
            $details[] = [
                'inventaire_id'   => $inventaireId,
                'produit_id'      => $produit['id'],
                'stock_theorique' => $produit['stock_actuel'],
                'stock_physique'  => 0,
                'ecart'           => -$produit['stock_actuel'],
                'valeur_ecart'    => -$produit['stock_actuel'] * $produit['prix_moyen'],
            ];
        }

        if (!empty($details)) {
            return $this->insertBatch($details);
        }

        return true;
    }

    /**
     * Met à jour le stock physique d'un produit dans l'inventaire
     */
    public function updateStockPhysique($inventaireId, $produitId, $stockPhysique)
    {
        $detail = $this->where('inventaire_id', $inventaireId)
            ->where('produit_id', $produitId)
            ->first();

        if (!$detail) {
            return false;
        }

        $produitModel = new StockProduitModel();
        $produit = $produitModel->find($produitId);

        $ecart = $stockPhysique - $detail['stock_theorique'];
        $valeurEcart = $ecart * ($produit['prix_moyen'] ?? 0);

        return $this->update($detail['id'], [
            'stock_physique' => $stockPhysique,
            'ecart'          => $ecart,
            'valeur_ecart'   => $valeurEcart,
        ]);
    }

    /**
     * Récupère le résumé des écarts d'un inventaire
     */
    public function getResumeEcarts($inventaireId)
    {
        $details = $this->where('inventaire_id', $inventaireId)->findAll();

        $ecartPositif = 0;
        $ecartNegatif = 0;
        $nbEcarts = 0;
        $valeurTotaleEcarts = 0;

        foreach ($details as $detail) {
            if ($detail['ecart'] != 0) {
                $nbEcarts++;

                if ($detail['ecart'] > 0) {
                    $ecartPositif += $detail['valeur_ecart'];
                } else {
                    $ecartNegatif += abs($detail['valeur_ecart']);
                }

                $valeurTotaleEcarts += $detail['valeur_ecart'];
            }
        }

        return [
            'nb_ecarts'            => $nbEcarts,
            'ecart_positif'        => $ecartPositif,
            'ecart_negatif'        => $ecartNegatif,
            'valeur_totale_ecarts' => $valeurTotaleEcarts,
        ];
    }
}
