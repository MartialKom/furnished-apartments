<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FactureEauModel;
use App\Models\ContratLocataireModel;
use CodeIgniter\HTTP\ResponseInterface;

class FactureEauController extends BaseController
{
    protected $factureEauModel;
    protected $contratModel;

    public function __construct()
    {
        $this->factureEauModel = new FactureEauModel();
        $this->contratModel = new ContratLocataireModel();
    }

    /**
     * Liste toutes les factures d'eau
     */
    public function index()
    {
        $factures = $this->factureEauModel->getFacturesWithDetails();

        // Organiser les factures par statut
        $facturesParStatut = [
            'en_attente' => [],
            'paye' => [],
            'en_retard' => [],
            'partiellement_paye' => []
        ];

        foreach ($factures as $facture) {
            $facturesParStatut[$facture['statut']][] = $facture;
        }

        // Récupérer tous les contrats actifs pour le formulaire de création
        $contrats = $this->contratModel
            ->select('contrats_locataires.*,
                      locataires.nom as locataire_nom,
                      appartements.adresse as appartement_adresse')
            ->join('locataires', 'locataires.id = contrats_locataires.locataire_id')
            ->join('appartements', 'appartements.id = contrats_locataires.appartement_id')
            ->where('contrats_locataires.statut', 'actif')
            ->findAll();

        $data = [
            'title' => 'Gestion des Factures d\'Eau',
            'page_title' => 'Factures d\'Eau',
            'breadcrumbs' => '<li class="breadcrumb-item">Administration</li><li class="breadcrumb-item active">Factures d\'Eau</li>',
            'factures' => $facturesParStatut,
            'contrats' => $contrats
        ];

        return view('admin/pages/factures_eau/index', $data);
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        // Récupérer tous les contrats actifs
        $contrats = $this->contratModel
            ->select('contrats_locataires.*,
                      locataires.nom as locataire_nom,
                      appartements.adresse as appartement_adresse')
            ->join('locataires', 'locataires.id = contrats_locataires.locataire_id')
            ->join('appartements', 'appartements.id = contrats_locataires.appartement_id')
            ->where('contrats_locataires.statut', 'actif')
            ->findAll();

        $data = [
            'title' => 'Créer une Facture d\'Eau',
            'page_title' => 'Nouvelle Facture d\'Eau',
            'breadcrumbs' => '<li class="breadcrumb-item">Administration</li><li class="breadcrumb-item"><a href="' . base_url('admin/factures-eau') . '">Factures d\'Eau</a></li><li class="breadcrumb-item active">Nouvelle</li>',
            'contrats' => $contrats
        ];

        return view('admin/pages/factures_eau/create', $data);
    }

    /**
     * Enregistrer une nouvelle facture
     */
    public function store()
    {
        // Récupérer les données JSON
        $json = $this->request->getJSON(true); // true pour retourner un array

        log_message('info', '=== DÉBUT CRÉATION FACTURE D\'EAU ===');
        log_message('info', 'Données JSON reçues: ' . json_encode($json));

        $rules = [
            'contrat_id' => 'required|integer',
            'mois_annee' => 'required|regex_match[/^\d{4}-\d{2}$/]',
            'montant' => 'required|decimal|greater_than[0]',
            'consommation_m3' => 'permit_empty|decimal',
            'index_precedent' => 'permit_empty|decimal',
            'index_actuel' => 'permit_empty|decimal',
            'date_emission' => 'required|valid_date',
            'date_echeance' => 'required|valid_date',
            'notes' => 'permit_empty|string'
        ];

        // Valider les données JSON
        if (!$this->validate($rules, $json)) {
            $errors = $this->validator->getErrors();
            log_message('error', 'VALIDATION ÉCHOUÉE: ' . json_encode($errors));
            return $this->response->setJSON([
                'success' => false,
                'errors' => $errors
            ]);
        }

        log_message('info', 'Validation réussie');

        // Vérifier si une facture existe déjà pour ce contrat et ce mois
        $contratId = $json['contrat_id'] ?? null;
        $moisAnnee = $json['mois_annee'] ?? null;

        $factureExistante = $this->factureEauModel
            ->where('contrat_id', $contratId)
            ->where('mois_annee', $moisAnnee)
            ->first();

        if ($factureExistante) {
            log_message('warning', 'Facture déjà existante pour ce contrat et ce mois');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Une facture existe déjà pour ce contrat et ce mois.'
            ]);
        }

        $data = [
            'contrat_id' => $contratId,
            'mois_annee' => $moisAnnee,
            'montant' => $json['montant'] ?? null,
            'consommation_m3' => $json['consommation_m3'] ?? null,
            'index_precedent' => $json['index_precedent'] ?? null,
            'index_actuel' => $json['index_actuel'] ?? null,
            'date_emission' => $json['date_emission'] ?? null,
            'date_echeance' => $json['date_echeance'] ?? null,
            'statut' => 'en_attente',
            'notes' => $json['notes'] ?? null,
            'enregistre_par' => session()->get('user_id')
        ];

        log_message('info', 'Données de facture à insérer: ' . json_encode($data));

        try {
            $factureId = $this->factureEauModel->creerFacture($data);

            if ($factureId) {
                log_message('info', "Facture créée avec succès - ID: {$factureId}");
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Facture d\'eau créée avec succès !'
                ]);
            } else {
                $modelErrors = $this->factureEauModel->errors();
                log_message('error', 'Échec création facture - Erreurs du modèle: ' . json_encode($modelErrors));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de la création de la facture.',
                    'errors' => $modelErrors
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'EXCEPTION lors de la création: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Récupérer une facture
     */
    public function get($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Facture non trouvée.'
            ]);
        }

        $facture = $this->factureEauModel
            ->select('factures_eau.*,
                      locataires.nom as locataire_nom,
                      locataires.email as locataire_email,
                      locataires.telephone as locataire_telephone,
                      appartements.adresse as appartement_adresse,
                      contrats_locataires.loyer_mensuel')
            ->join('contrats_locataires', 'contrats_locataires.id = factures_eau.contrat_id')
            ->join('locataires', 'locataires.id = contrats_locataires.locataire_id')
            ->join('appartements', 'appartements.id = contrats_locataires.appartement_id')
            ->find($id);

        if (!$facture) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Facture non trouvée.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'facture' => $facture
        ]);
    }

    /**
     * Marquer une facture comme payée
     */
    public function marquerPaye($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Facture non trouvée.'
            ]);
        }

        // Récupérer les données JSON
        $json = $this->request->getJSON(true);

        $rules = [
            'montant_paye' => 'required|decimal|greater_than[0]',
            'date_paiement' => 'required|valid_date',
            'mode_paiement' => 'required|in_list[especes,virement,cheque,mobile_money]',
            'reference_paiement' => 'permit_empty|string|max_length[255]'
        ];

        if (!$this->validate($rules, $json)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $facture = $this->factureEauModel->find($id);
        if (!$facture) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Facture non trouvée.'
            ]);
        }

        $montantPaye = $json['montant_paye'] ?? 0;

        // Vérifier que le montant payé ne dépasse pas le montant dû
        if ($montantPaye > $facture['montant']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Le montant payé ne peut pas dépasser le montant de la facture.'
            ]);
        }

        $data = [
            'montant_paye' => $montantPaye,
            'date_paiement' => $json['date_paiement'] ?? null,
            'mode_paiement' => $json['mode_paiement'] ?? null,
            'reference_paiement' => $json['reference_paiement'] ?? null
        ];

        // Déterminer le statut en fonction du montant payé
        if ($montantPaye >= $facture['montant']) {
            $data['statut'] = 'paye';
        } else {
            $data['statut'] = 'partiellement_paye';
        }

        if ($this->factureEauModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Paiement enregistré avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement du paiement.'
            ]);
        }
    }

    /**
     * Modifier une facture
     */
    public function update($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Facture non trouvée.'
            ]);
        }

        // Récupérer les données JSON
        $json = $this->request->getJSON(true);

        $rules = [
            'montant' => 'required|decimal|greater_than[0]',
            'consommation_m3' => 'permit_empty|decimal',
            'index_precedent' => 'permit_empty|decimal',
            'index_actuel' => 'permit_empty|decimal',
            'date_emission' => 'required|valid_date',
            'date_echeance' => 'required|valid_date',
            'notes' => 'permit_empty|string'
        ];

        if (!$this->validate($rules, $json)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $facture = $this->factureEauModel->find($id);
        if (!$facture) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Facture non trouvée.'
            ]);
        }

        // Ne pas permettre la modification si déjà payée
        if ($facture['statut'] === 'paye') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Impossible de modifier une facture déjà payée.'
            ]);
        }

        $data = [
            'montant' => $json['montant'] ?? null,
            'consommation_m3' => $json['consommation_m3'] ?? null,
            'index_precedent' => $json['index_precedent'] ?? null,
            'index_actuel' => $json['index_actuel'] ?? null,
            'date_emission' => $json['date_emission'] ?? null,
            'date_echeance' => $json['date_echeance'] ?? null,
            'notes' => $json['notes'] ?? null
        ];

        if ($this->factureEauModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Facture mise à jour avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la facture.'
            ]);
        }
    }

    /**
     * Supprimer une facture
     */
    public function delete($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Facture non trouvée.'
            ]);
        }

        $facture = $this->factureEauModel->find($id);
        if (!$facture) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Facture non trouvée.'
            ]);
        }

        // Ne pas permettre la suppression si déjà payée
        if ($facture['statut'] === 'paye') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Impossible de supprimer une facture déjà payée.'
            ]);
        }

        if ($this->factureEauModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Facture supprimée avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la facture.'
            ]);
        }
    }

    /**
     * Générer les factures pour le mois en cours
     */
    public function genererFacturesMois()
    {
        try {
            $facturesCreees = $this->factureEauModel->genererFacturesMoisActuel();

            return $this->response->setJSON([
                'success' => true,
                'message' => "{$facturesCreees} facture(s) générée(s) pour le mois en cours."
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Erreur génération factures: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la génération des factures: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Mettre à jour les statuts en retard
     */
    public function updateStatutsRetard()
    {
        try {
            $this->factureEauModel->updateStatutsEnRetard();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Statuts mis à jour avec succès.'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Erreur mise à jour statuts: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour des statuts.'
            ]);
        }
    }
}
