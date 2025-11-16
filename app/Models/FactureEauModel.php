<?php

namespace App\Models;

use CodeIgniter\Model;

class FactureEauModel extends Model
{
    protected $table            = 'factures_eau';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'contrat_id',
        'mois_annee',
        'montant',
        'consommation_m3',
        'index_precedent',
        'index_actuel',
        'date_emission',
        'date_echeance',
        'date_paiement',
        'statut',
        'montant_paye',
        'mode_paiement',
        'reference_paiement',
        'notes',
        'enregistre_par'
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
        'contrat_id' => 'required|integer',
        'mois_annee' => 'required|regex_match[/^\d{4}-\d{2}$/]',
        'montant' => 'required|decimal|greater_than[0]',
        'consommation_m3' => 'permit_empty|decimal',
        'index_precedent' => 'permit_empty|decimal',
        'index_actuel' => 'permit_empty|decimal',
        'date_emission' => 'required|valid_date',
        'date_echeance' => 'required|valid_date',
        'date_paiement' => 'permit_empty|valid_date',
        'statut' => 'required|in_list[en_attente,paye,en_retard,partiellement_paye]',
        'montant_paye' => 'permit_empty|decimal',
        'mode_paiement' => 'permit_empty|in_list[especes,virement,cheque,mobile_money]',
        'reference_paiement' => 'permit_empty|string|max_length[255]',
        'notes' => 'permit_empty|string',
        'enregistre_par' => 'permit_empty|integer'
    ];

    protected $validationMessages   = [
        'contrat_id' => [
            'required' => 'Le contrat est obligatoire.',
            'integer' => 'Le contrat doit être un entier.'
        ],
        'mois_annee' => [
            'required' => 'Le mois/année est obligatoire.',
            'regex_match' => 'Le format du mois/année doit être YYYY-MM.'
        ],
        'montant' => [
            'required' => 'Le montant est obligatoire.',
            'decimal' => 'Le montant doit être un nombre.',
            'greater_than' => 'Le montant doit être supérieur à 0.'
        ],
        'date_emission' => [
            'required' => 'La date d\'émission est obligatoire.',
            'valid_date' => 'La date d\'émission doit être valide.'
        ],
        'date_echeance' => [
            'required' => 'La date d\'échéance est obligatoire.',
            'valid_date' => 'La date d\'échéance doit être valide.'
        ],
        'statut' => [
            'required' => 'Le statut est obligatoire.',
            'in_list' => 'Le statut doit être: en_attente, paye, en_retard ou partiellement_paye.'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Créer une facture d'eau
     */
    public function creerFacture($data)
    {
        log_message('info', '[FactureEauModel] Création facture d\'eau: ' . json_encode($data));

        // Calculer automatiquement la consommation si les index sont fournis
        if (!empty($data['index_actuel']) && !empty($data['index_precedent'])) {
            $data['consommation_m3'] = $data['index_actuel'] - $data['index_precedent'];
        }

        return $this->insert($data);
    }

    /**
     * Récupérer les factures avec les informations du contrat et du locataire
     */
    public function getFacturesWithDetails()
    {
        return $this->select('factures_eau.*,
                              locataires.nom as locataire_nom,
                              locataires.email as locataire_email,
                              locataires.telephone as locataire_telephone,
                              appartements.adresse as appartement_adresse,
                              contrats_locataires.loyer_mensuel')
                    ->join('contrats_locataires', 'contrats_locataires.id = factures_eau.contrat_id')
                    ->join('locataires', 'locataires.id = contrats_locataires.locataire_id')
                    ->join('appartements', 'appartements.id = contrats_locataires.appartement_id')
                    ->orderBy('factures_eau.date_emission', 'DESC')
                    ->findAll();
    }

    /**
     * Récupérer les factures par contrat
     */
    public function getFacturesByContrat($contratId)
    {
        return $this->where('contrat_id', $contratId)
                    ->orderBy('mois_annee', 'DESC')
                    ->findAll();
    }

    /**
     * Récupérer les factures impayées
     */
    public function getFacturesImpayees()
    {
        return $this->select('factures_eau.*,
                              locataires.nom as locataire_nom,
                              locataires.email as locataire_email,
                              locataires.telephone as locataire_telephone,
                              appartements.adresse as appartement_adresse')
                    ->join('contrats_locataires', 'contrats_locataires.id = factures_eau.contrat_id')
                    ->join('locataires', 'locataires.id = contrats_locataires.locataire_id')
                    ->join('appartements', 'appartements.id = contrats_locataires.appartement_id')
                    ->whereIn('factures_eau.statut', ['en_attente', 'en_retard', 'partiellement_paye'])
                    ->orderBy('factures_eau.date_echeance', 'ASC')
                    ->findAll();
    }

    /**
     * Marquer une facture comme payée
     */
    public function marquerCommePaye($factureId, $data)
    {
        log_message('info', '[FactureEauModel] Marquage facture comme payée - ID: ' . $factureId);

        $updateData = [
            'statut' => 'paye',
            'montant_paye' => $data['montant_paye'] ?? null,
            'date_paiement' => $data['date_paiement'] ?? date('Y-m-d'),
            'mode_paiement' => $data['mode_paiement'] ?? null,
            'reference_paiement' => $data['reference_paiement'] ?? null
        ];

        return $this->update($factureId, $updateData);
    }

    /**
     * Générer automatiquement les factures pour tous les contrats actifs
     */
    public function genererFacturesMoisActuel()
    {
        log_message('info', '[FactureEauModel] Génération factures du mois en cours');

        $contratModel = new \App\Models\ContratLocataireModel();
        $contratsActifs = $contratModel->where('statut', 'actif')->findAll();

        $moisActuel = date('Y-m');
        $dateEmission = date('Y-m-d');
        $dateEcheance = date('Y-m-') . '25'; // Échéance le 25 du mois

        $facturesCreees = 0;

        foreach ($contratsActifs as $contrat) {
            // Vérifier si la facture existe déjà pour ce mois
            $factureExistante = $this->where('contrat_id', $contrat['id'])
                                     ->where('mois_annee', $moisActuel)
                                     ->first();

            if (!$factureExistante) {
                // Créer la facture (montant par défaut = 0, à remplir manuellement)
                $dataFacture = [
                    'contrat_id' => $contrat['id'],
                    'mois_annee' => $moisActuel,
                    'montant' => 0.00,
                    'date_emission' => $dateEmission,
                    'date_echeance' => $dateEcheance,
                    'statut' => 'en_attente'
                ];

                if ($this->insert($dataFacture)) {
                    $facturesCreees++;
                }
            }
        }

        log_message('info', "[FactureEauModel] {$facturesCreees} factures créées pour le mois {$moisActuel}");
        return $facturesCreees;
    }

    /**
     * Mettre à jour le statut des factures en retard
     */
    public function updateStatutsEnRetard()
    {
        log_message('info', '[FactureEauModel] Mise à jour des statuts en retard');

        $dateAujourdhui = date('Y-m-d');

        return $this->where('statut', 'en_attente')
                    ->where('date_echeance <', $dateAujourdhui)
                    ->set(['statut' => 'en_retard'])
                    ->update();
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
