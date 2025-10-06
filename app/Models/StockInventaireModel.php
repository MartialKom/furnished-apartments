<?php

namespace App\Models;

use CodeIgniter\Model;

class StockInventaireModel extends Model
{
    protected $table            = 'stock_inventaires';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'numero_inventaire',
        'date_inventaire',
        'statut',
        'observations',
        'utilisateur_id',
        'valide_par',
        'date_validation',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'numero_inventaire' => 'required|is_unique[stock_inventaires.numero_inventaire,id,{id}]',
        'date_inventaire'   => 'required|valid_date',
        'utilisateur_id'    => 'required|integer',
    ];

    protected $validationMessages = [
        'numero_inventaire' => [
            'required'  => 'Le numéro d\'inventaire est requis',
            'is_unique' => 'Ce numéro d\'inventaire existe déjà',
        ],
        'date_inventaire' => [
            'required'   => 'La date d\'inventaire est requise',
            'valid_date' => 'La date n\'est pas valide',
        ],
    ];

    /**
     * Génère un numéro d'inventaire unique
     */
    public function genererNumeroInventaire()
    {
        $date = date('Ymd');
        $count = $this->where('DATE(date_inventaire)', date('Y-m-d'))->countAllResults();
        $numero = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return "INV-{$date}-{$numero}";
    }

    /**
     * Récupère tous les inventaires avec détails
     */
    public function getInventairesAvecDetails()
    {
        return $this->select('stock_inventaires.*,
                             utilisateurs.nom as utilisateur_nom,
                             u2.nom as validateur_nom')
            ->join('utilisateurs', 'utilisateurs.id = stock_inventaires.utilisateur_id')
            ->join('utilisateurs u2', 'u2.id = stock_inventaires.valide_par', 'left')
            ->orderBy('stock_inventaires.date_inventaire', 'DESC')
            ->findAll();
    }

    /**
     * Récupère un inventaire avec ses détails
     */
    public function getInventaireComplet($id)
    {
        $inventaire = $this->select('stock_inventaires.*,
                                    utilisateurs.nom as utilisateur_nom,
                                    u2.nom as validateur_nom')
            ->join('utilisateurs', 'utilisateurs.id = stock_inventaires.utilisateur_id')
            ->join('utilisateurs u2', 'u2.id = stock_inventaires.valide_par', 'left')
            ->where('stock_inventaires.id', $id)
            ->first();

        if (!$inventaire) {
            return null;
        }

        // Récupérer les détails
        $detailModel = new StockInventaireDetailModel();
        $inventaire['details'] = $detailModel->getDetailsInventaire($id);

        return $inventaire;
    }

    /**
     * Valide un inventaire
     */
    public function validerInventaire($inventaireId, $utilisateurId)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Mettre à jour le statut
            $this->update($inventaireId, [
                'statut'          => 'valide',
                'valide_par'      => $utilisateurId,
                'date_validation' => date('Y-m-d H:i:s'),
            ]);

            // Ajuster les stocks selon l'inventaire
            $detailModel = new StockInventaireDetailModel();
            $produitModel = new StockProduitModel();

            $details = $detailModel->where('inventaire_id', $inventaireId)->findAll();

            foreach ($details as $detail) {
                $produitModel->ajusterStock($detail['produit_id'], $detail['stock_physique']);
            }

            $db->transComplete();

            return $db->transStatus();
        } catch (\Exception $e) {
            $db->transRollback();
            return false;
        }
    }

    /**
     * Statistiques des inventaires
     */
    public function getStatistiques()
    {
        $total = $this->countAll();
        $enCours = $this->where('statut', 'en_cours')->countAllResults();
        $termines = $this->where('statut', 'termine')->countAllResults();
        $valides = $this->where('statut', 'valide')->countAllResults();

        return [
            'total'    => $total,
            'en_cours' => $enCours,
            'termines' => $termines,
            'valides'  => $valides,
        ];
    }
}
