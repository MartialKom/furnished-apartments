<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StockCategorieModel;
use App\Models\StockProduitModel;
use App\Models\StockApprovisionnementModel;
use App\Models\StockSortieModel;
use App\Models\StockInventaireModel;
use App\Models\StockInventaireDetailModel;
use App\Models\AppartementModel;
use App\Models\StructureParamModel;

class StockController extends BaseController
{
    protected $categorieModel;
    protected $produitModel;
    protected $approvisionnementModel;
    protected $sortieModel;
    protected $inventaireModel;
    protected $inventaireDetailModel;
    protected $appartementModel;
    protected $structureParamModel;

    public function __construct()
    {
        $this->categorieModel = new StockCategorieModel();
        $this->produitModel = new StockProduitModel();
        $this->approvisionnementModel = new StockApprovisionnementModel();
        $this->sortieModel = new StockSortieModel();
        $this->inventaireModel = new StockInventaireModel();
        $this->inventaireDetailModel = new StockInventaireDetailModel();
        $this->appartementModel = new AppartementModel();
        $this->structureParamModel = new StructureParamModel();
    }

    // =====================================================
    // DASHBOARD
    // =====================================================

    public function index()
    {
        // Récupérer les statistiques
        $totalProduits = $this->produitModel->where('actif', 1)->countAllResults();
        $totalCategories = $this->categorieModel->where('actif', 1)->countAllResults();

        $data = [
            'title' => 'Tableau de Bord Stock',
            'produitsAlerte' => $this->produitModel->getProduitsEnAlerte(),
            'valeurStock' => $this->produitModel->getValeurTotaleStock(),
            'statistiques' => [
                'total_produits' => $totalProduits,
                'total_categories' => $totalCategories,
                'sorties_mois' => $this->sortieModel->getStatistiques(
                    date('Y-m-01'),
                    date('Y-m-t')
                ),
                'approvisionnements_mois' => $this->approvisionnementModel->getStatistiques(
                    date('Y-m-01'),
                    date('Y-m-t')
                ),
            ]
        ];

        return view('admin/pages/stock/dashboard', $data);
    }

    // =====================================================
    // CATEGORIES
    // =====================================================

    public function categories()
    {
        $data = [
            'title' => 'Catégories de produits',
            'categories' => $this->categorieModel->select('stock_categories.*, COUNT(stock_produits.id) as nb_produits')
                ->join('stock_produits', 'stock_produits.categorie_id = stock_categories.id', 'left')
                ->groupBy('stock_categories.id')
                ->orderBy('stock_categories.nom', 'ASC')
                ->paginate(25),
            'pager' => $this->categorieModel->pager,
        ];

        return view('admin/pages/stock/categories/index', $data);
    }

    public function createCategorie()
    {
        log_message('info', '=== DEBUT createCategorie ===');
        log_message('info', 'Method: ' . $this->request->getMethod());
        log_message('info', 'POST data: ' . json_encode($this->request->getPost()));

        if (strtolower($this->request->getMethod()) === 'post') {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'nom' => 'required|min_length[3]|max_length[100]',
            ]);

            log_message('info', 'Validation des données...');

            if (!$validation->withRequest($this->request)->run()) {
                log_message('error', 'Validation échouée pour création catégorie: ' . json_encode($validation->getErrors()));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation échouée',
                    'errors' => $validation->getErrors()
                ]);
            }

            log_message('info', 'Validation réussie');

            $data = [
                'nom' => $this->request->getPost('nom'),
                'description' => $this->request->getPost('description'),
                'actif' => 1,
            ];

            log_message('info', 'Tentative de création de catégorie: ' . json_encode($data));

            try {
                $categorieId = $this->categorieModel->insert($data);

                log_message('info', 'Insert retourné: ' . var_export($categorieId, true));

                if ($categorieId) {
                    log_message('info', 'Catégorie créée avec succès, ID: ' . $categorieId);
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Catégorie créée avec succès',
                        'id' => $categorieId
                    ]);
                }

                $errors = $this->categorieModel->errors();
                log_message('error', 'Erreur model: ' . json_encode($errors));
                log_message('error', 'Insert a échoué sans erreur explicite');

                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de la création: ' . (empty($errors) ? 'Erreur inconnue - vérifiez les logs' : implode(', ', $errors)),
                    'errors' => $errors
                ]);
            } catch (\Exception $e) {
                log_message('error', 'Exception lors de la création: ' . $e->getMessage());
                log_message('error', 'Stack trace: ' . $e->getTraceAsString());

                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Exception: ' . $e->getMessage(),
                    'errors' => []
                ]);
            }
        }

        log_message('warning', 'Méthode HTTP incorrecte: ' . $this->request->getMethod());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Méthode HTTP incorrecte'
        ]);
    }

    public function getCategorie($id)
    {
        $categorie = $this->categorieModel->find($id);

        if ($categorie) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $categorie
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Catégorie introuvable'
        ]);
    }

    public function updateCategorie($id)
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'nom' => 'required|min_length[3]|max_length[100]',
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $validation->getErrors()
                ]);
            }

            $data = [
                'nom' => $this->request->getPost('nom'),
                'description' => $this->request->getPost('description'),
            ];

            if ($this->categorieModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Catégorie modifiée avec succès'
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la modification'
            ]);
        }
    }

    public function toggleCategorieStatus($id)
    {
        $categorie = $this->categorieModel->find($id);

        if (!$categorie) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Catégorie introuvable'
            ]);
        }

        $newStatus = $categorie['actif'] == 1 ? 0 : 1;

        if ($this->categorieModel->update($id, ['actif' => $newStatus])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Statut modifié avec succès',
                'newStatus' => $newStatus
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Erreur lors de la modification'
        ]);
    }

    public function deleteCategorie($id)
    {
        // Vérifier s'il y a des produits dans cette catégorie
        $nbProduits = $this->produitModel->where('categorie_id', $id)->countAllResults();

        if ($nbProduits > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Impossible de supprimer une catégorie contenant des produits'
            ]);
        }

        if ($this->categorieModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Catégorie supprimée avec succès'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Erreur lors de la suppression'
        ]);
    }

    // =====================================================
    // PRODUITS
    // =====================================================

    public function produits()
    {
        $data = [
            'title' => 'Gestion des produits',
            'produits' => $this->produitModel->select('stock_produits.*, stock_categories.nom as categorie_nom')
                ->join('stock_categories', 'stock_categories.id = stock_produits.categorie_id')
                ->orderBy('stock_categories.nom, stock_produits.nom', 'ASC')
                ->paginate(25),
            'pager' => $this->produitModel->pager,
            'categories' => $this->categorieModel->getCategoriesActives(),
        ];

        return view('admin/pages/stock/produits/index', $data);
    }

    public function createProduit()
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            $data = [
                'categorie_id' => $this->request->getPost('categorie_id'),
                'nom' => $this->request->getPost('nom'),
                'description' => $this->request->getPost('description'),
                'unite_mesure' => $this->request->getPost('unite_mesure'),
                'stock_minimum' => $this->request->getPost('stock_minimum') ?: 0,
                'stock_actuel' => 0,
                'prix_moyen' => 0,
                'actif' => 1,
            ];

            if ($this->produitModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Produit créé avec succès'
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la création',
                'errors' => $this->produitModel->errors()
            ]);
        }
    }

    public function getProduit($id)
    {
        $produit = $this->produitModel->getProduitAvecCategorie($id);

        if ($produit) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $produit
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Produit introuvable'
        ]);
    }

    public function updateProduit($id)
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            $data = [
                'categorie_id' => $this->request->getPost('categorie_id'),
                'nom' => $this->request->getPost('nom'),
                'description' => $this->request->getPost('description'),
                'unite_mesure' => $this->request->getPost('unite_mesure'),
                'stock_minimum' => $this->request->getPost('stock_minimum') ?: 0,
            ];

            if ($this->produitModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Produit modifié avec succès'
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la modification',
                'errors' => $this->produitModel->errors()
            ]);
        }
    }

    public function toggleProduitStatus($id)
    {
        $produit = $this->produitModel->find($id);

        if (!$produit) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Produit introuvable'
            ]);
        }

        $newStatus = $produit['actif'] == 1 ? 0 : 1;

        if ($this->produitModel->update($id, ['actif' => $newStatus])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Statut modifié avec succès',
                'newStatus' => $newStatus
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Erreur lors de la modification'
        ]);
    }

    public function deleteProduit($id)
    {
        if ($this->produitModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Produit supprimé avec succès'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Erreur lors de la suppression'
        ]);
    }

    // =====================================================
    // APPROVISIONNEMENTS
    // =====================================================

    public function approvisionnements()
    {
        $data = [
            'title' => 'Approvisionnements',
            'approvisionnements' => $this->approvisionnementModel->select('stock_approvisionnements.*,
                             stock_produits.nom as produit_nom,
                             stock_produits.unite_mesure,
                             stock_categories.nom as categorie_nom,
                             utilisateurs.nom as utilisateur_nom')
                ->join('stock_produits', 'stock_produits.id = stock_approvisionnements.produit_id')
                ->join('stock_categories', 'stock_categories.id = stock_produits.categorie_id')
                ->join('utilisateurs', 'utilisateurs.id = stock_approvisionnements.utilisateur_id')
                ->orderBy('stock_approvisionnements.date_approvisionnement', 'DESC')
                ->paginate(25),
            'pager' => $this->approvisionnementModel->pager,
            'produits' => $this->produitModel->getProduitsActifs(),
        ];

        return view('admin/pages/stock/approvisionnements/index', $data);
    }

    public function createApprovisionnement()
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            $db = \Config\Database::connect();
            $db->transStart();

            try {
                $data = [
                    'produit_id' => $this->request->getPost('produit_id'),
                    'quantite' => $this->request->getPost('quantite'),
                    'prix_unitaire' => $this->request->getPost('prix_unitaire'),
                    'prix_total' => $this->request->getPost('quantite') * $this->request->getPost('prix_unitaire'),
                    'fournisseur' => $this->request->getPost('fournisseur'),
                    'reference_facture' => $this->request->getPost('reference_facture'),
                    'date_approvisionnement' => $this->request->getPost('date_approvisionnement'),
                    'notes' => $this->request->getPost('notes'),
                    'utilisateur_id' => session()->get('user_id'),
                ];

                if ($this->approvisionnementModel->insert($data)) {
                    // Mettre à jour le stock et le prix moyen
                    $this->produitModel->ajouterStock(
                        $data['produit_id'],
                        $data['quantite'],
                        $data['prix_unitaire']
                    );

                    $db->transComplete();

                    if ($db->transStatus()) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Approvisionnement enregistré avec succès'
                        ]);
                    }
                }

                throw new \Exception('Erreur lors de l\'enregistrement');
            } catch (\Exception $e) {
                $db->transRollback();

                return $this->response->setJSON([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => $this->approvisionnementModel->errors()
                ]);
            }
        }
    }

    public function getApprovisionnement($id)
    {
        $approvisionnement = $this->approvisionnementModel->find($id);

        if ($approvisionnement) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $approvisionnement
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Approvisionnement introuvable'
        ]);
    }

    // =====================================================
    // SORTIES
    // =====================================================

    public function sorties()
    {
        $data = [
            'title' => 'Sorties de stock',
            'sorties' => $this->sortieModel->select('stock_sorties.*,
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
                ->paginate(25),
            'pager' => $this->sortieModel->pager,
            'produits' => $this->produitModel->getProduitsActifs(),
            'appartements' => $this->appartementModel->findAll(),
        ];

        return view('admin/pages/stock/sorties/index', $data);
    }

    public function createSortie()
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            $db = \Config\Database::connect();
            $db->transStart();

            try {
                $produitId = $this->request->getPost('produit_id');
                $quantite = $this->request->getPost('quantite');

                // Vérifier le stock disponible
                $produit = $this->produitModel->find($produitId);

                if (!$produit) {
                    throw new \Exception('Produit introuvable');
                }

                if ($produit['stock_actuel'] < $quantite) {
                    throw new \Exception('Stock insuffisant. Disponible: ' . $produit['stock_actuel'] . ' ' . $produit['unite_mesure']);
                }

                $data = [
                    'produit_id' => $produitId,
                    'quantite' => $quantite,
                    'appartement_id' => $this->request->getPost('appartement_id') ?: null,
                    'destination' => $this->request->getPost('destination'),
                    'motif' => $this->request->getPost('motif'),
                    'date_sortie' => $this->request->getPost('date_sortie'),
                    'notes' => $this->request->getPost('notes'),
                    'utilisateur_id' => session()->get('user_id'),
                ];

                if ($this->sortieModel->insert($data)) {
                    // Retirer du stock
                    $this->produitModel->retirerStock($produitId, $quantite);

                    $db->transComplete();

                    if ($db->transStatus()) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Sortie enregistrée avec succès'
                        ]);
                    }
                }

                throw new \Exception('Erreur lors de l\'enregistrement');
            } catch (\Exception $e) {
                $db->transRollback();

                return $this->response->setJSON([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => $this->sortieModel->errors()
                ]);
            }
        }
    }

    public function getSortie($id)
    {
        $sortie = $this->sortieModel->find($id);

        if ($sortie) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $sortie
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Sortie introuvable'
        ]);
    }

    // =====================================================
    // INVENTAIRES
    // =====================================================

    public function inventaires()
    {
        $data = [
            'title' => 'Inventaires',
            'inventaires' => $this->inventaireModel->select('stock_inventaires.*,
                             utilisateurs.nom as utilisateur_nom,
                             u2.nom as validateur_nom')
                ->join('utilisateurs', 'utilisateurs.id = stock_inventaires.utilisateur_id')
                ->join('utilisateurs u2', 'u2.id = stock_inventaires.valide_par', 'left')
                ->orderBy('stock_inventaires.date_inventaire', 'DESC')
                ->paginate(25),
            'pager' => $this->inventaireModel->pager,
            'statistiques' => $this->inventaireModel->getStatistiques(),
        ];

        return view('admin/pages/stock/inventaires/index', $data);
    }

    public function createInventaire()
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            $db = \Config\Database::connect();
            $db->transStart();

            try {
                $data = [
                    'numero_inventaire' => $this->inventaireModel->genererNumeroInventaire(),
                    'date_inventaire' => $this->request->getPost('date_inventaire'),
                    'observations' => $this->request->getPost('observations'),
                    'statut' => 'en_cours',
                    'utilisateur_id' => session()->get('user_id'),
                ];

                $inventaireId = $this->inventaireModel->insert($data);

                if ($inventaireId) {
                    // Initialiser les détails avec tous les produits actifs
                    $this->inventaireDetailModel->initialiserInventaire($inventaireId);

                    $db->transComplete();

                    if ($db->transStatus()) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Inventaire créé avec succès',
                            'inventaire_id' => $inventaireId
                        ]);
                    }
                }

                throw new \Exception('Erreur lors de la création');
            } catch (\Exception $e) {
                $db->transRollback();

                return $this->response->setJSON([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => $this->inventaireModel->errors()
                ]);
            }
        }
    }

    public function showInventaire($id)
    {
        $inventaire = $this->inventaireModel->getInventaireComplet($id);

        if (!$inventaire) {
            return redirect()->to('/admin/stock/inventaires')->with('error', 'Inventaire introuvable');
        }

        $data = [
            'title' => 'Inventaire ' . $inventaire['numero_inventaire'],
            'inventaire' => $inventaire,
            'resume' => $this->inventaireDetailModel->getResumeEcarts($id),
        ];

        return view('admin/pages/stock/inventaires/show', $data);
    }

    public function updateStockPhysique()
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            $inventaireId = $this->request->getPost('inventaire_id');
            $produitId = $this->request->getPost('produit_id');
            $stockPhysique = $this->request->getPost('stock_physique');

            if ($this->inventaireDetailModel->updateStockPhysique($inventaireId, $produitId, $stockPhysique)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Stock physique mis à jour'
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ]);
        }
    }

    public function terminerInventaire($id)
    {
        if ($this->inventaireModel->update($id, ['statut' => 'termine'])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Inventaire terminé avec succès'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour'
        ]);
    }

    public function validerInventaire($id)
    {
        if ($this->inventaireModel->validerInventaire($id, session()->get('user_id'))) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Inventaire validé avec succès. Les stocks ont été ajustés.'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Erreur lors de la validation'
        ]);
    }

    // =====================================================
    // RAPPORTS
    // =====================================================

    public function rapports()
    {
        $dateDebut = $this->request->getGet('date_debut') ?: date('Y-m-01');
        $dateFin = $this->request->getGet('date_fin') ?: date('Y-m-t');

        $data = [
            'title' => 'Rapports Stock',
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'produits' => $this->produitModel->getProduitsAvecCategorie(),
            'produitsAlerte' => $this->produitModel->getProduitsEnAlerte(),
            'sorties' => $this->sortieModel->getSortiesPeriode($dateDebut, $dateFin),
            'approvisionnements' => $this->approvisionnementModel->getApprovisionnementsPeriode($dateDebut, $dateFin),
            'statistiques' => [
                'valeur_stock' => $this->produitModel->getValeurTotaleStock(),
                'sorties' => $this->sortieModel->getStatistiques($dateDebut, $dateFin),
                'approvisionnements' => $this->approvisionnementModel->getStatistiques($dateDebut, $dateFin),
            ]
        ];

        return view('admin/pages/stock/rapports/index', $data);
    }

    public function imprimerStock()
    {
        $produits = $this->produitModel->getProduitsAvecCategorie();
        $structureParams = $this->structureParamModel->getStructureParams();

        $data = [
            'title' => 'État du Stock',
            'date' => date('d/m/Y'),
            'produits' => $produits,
            'valeur_totale' => $this->produitModel->getValeurTotaleStock(),
            'structure_nom' => $structureParams['structure_name'],
            'structure_adresse' => $structureParams['structure_address'],
            'structure_telephone' => $structureParams['structure_phone'],
            'structure_email' => $structureParams['structure_email'],
            'structure_logo' => $structureParams['structure_logo'],
        ];

        return view('admin/pages/stock/rapports/print_stock', $data);
    }

    public function imprimerSorties()
    {
        $dateDebut = $this->request->getGet('date_debut') ?: date('Y-m-01');
        $dateFin = $this->request->getGet('date_fin') ?: date('Y-m-t');

        $sorties = $this->sortieModel->getSortiesPeriode($dateDebut, $dateFin);
        $structureParams = $this->structureParamModel->getStructureParams();

        $data = [
            'title' => 'Historique des Sorties',
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'sorties' => $sorties,
            'statistiques' => $this->sortieModel->getStatistiques($dateDebut, $dateFin),
            'structure_nom' => $structureParams['structure_name'],
            'structure_adresse' => $structureParams['structure_address'],
            'structure_telephone' => $structureParams['structure_phone'],
            'structure_email' => $structureParams['structure_email'],
            'structure_logo' => $structureParams['structure_logo'],
        ];

        return view('admin/pages/stock/rapports/print_sorties', $data);
    }
}
