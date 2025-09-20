<?php

namespace App\Models;

use CodeIgniter\Model;

class ContratLocataireModel extends Model
{
    protected $table            = 'contrats_locataires';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'locataire_id', 'appartement_id', 'date_debut', 'date_fin', 
        'loyer_mensuel', 'jour_paiement', 'caution', 'statut', 
        'conditions_particulieres'
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
        'locataire_id' => 'required|integer',
        'appartement_id' => 'required|integer',
        'date_debut' => 'required|valid_date',
        'date_fin' => 'permit_empty|valid_date',
        'loyer_mensuel' => 'required|decimal',
        'jour_paiement' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[31]',
        'caution' => 'permit_empty|decimal',
        'statut' => 'required|in_list[actif,suspendu,termine]',
        'conditions_particulieres' => 'permit_empty|string'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];

    /**
     * Obtenir tous les contrats actifs
     */
    public function getContratsActifs()
    {
        return $this->select('contrats_locataires.*, l.nom as locataire_nom, l.email as locataire_email, 
                             l.telephone as locataire_telephone, a.adresse as appartement_adresse')
                    ->join('locataires l', 'l.id = contrats_locataires.locataire_id')
                    ->join('appartements a', 'a.id = contrats_locataires.appartement_id')
                    ->where('contrats_locataires.statut', 'actif')
                    ->findAll();
    }

    /**
     * Obtenir un contrat avec toutes les informations
     */
    public function getContratAvecDetails($contratId)
    {
        return $this->select('contrats_locataires.*, l.nom as locataire_nom, l.email as locataire_email, 
                             l.telephone as locataire_telephone, a.adresse as appartement_adresse,
                             a.photos as appartement_photos')
                    ->join('locataires l', 'l.id = contrats_locataires.locataire_id')
                    ->join('appartements a', 'a.id = contrats_locataires.appartement_id')
                    ->where('contrats_locataires.id', $contratId)
                    ->first();
    }
    
    /**
     * Obtenir les détails d'un contrat avec les informations du locataire et de l'appartement
     */
    public function getContratDetails($contratId)
    {
        return $this->select('contrats_locataires.*, l.nom as locataire_nom, l.email as locataire_email, 
                             l.telephone as locataire_telephone, a.adresse as appartement_adresse,
                             a.type as appartement_type, a.tarifs as appartement_tarifs')
                    ->join('locataires l', 'l.id = contrats_locataires.locataire_id')
                    ->join('appartements a', 'a.id = contrats_locataires.appartement_id')
                    ->where('contrats_locataires.id', $contratId)
                    ->first();
    }

    /**
     * Vérifier si un appartement est disponible pour un nouveau contrat
     */
    public function verifierDisponibiliteAppartement($appartementId, $dateDebut, $dateFin = null)
    {
        $query = $this->where('appartement_id', $appartementId)
                      ->where('statut', 'actif')
                      ->where('date_debut <=', $dateFin ?? date('Y-m-d'))
                      ->where('(date_fin IS NULL OR date_fin >= "' . $dateDebut . '")');

        return $query->countAllResults() == 0;
    }

    /**
     * Obtenir les contrats avec échéances proches (5 jours)
     */
    public function getContratsAvecEcheancesProches()
    {
        $aujourdhui = date('Y-m-d');
        $dans5Jours = date('Y-m-d', strtotime('+5 days'));
        
        return $this->select('contrats_locataires.*, l.nom as locataire_nom, l.email as locataire_email')
                    ->join('locataires l', 'l.id = contrats_locataires.locataire_id')
                    ->where('contrats_locataires.statut', 'actif')
                    ->where('DAYOFMONTH(CURDATE()) >= (contrats_locataires.jour_paiement - 5)')
                    ->where('DAYOFMONTH(CURDATE()) <= contrats_locataires.jour_paiement')
                    ->findAll();
    }

    /**
     * Générer les échéances mensuelles pour un contrat
     */
    public function genererEcheancesMensuelles($contratId, $nombreMois = 12)
    {
        $contrat = $this->find($contratId);
        if (!$contrat) {
            return false;
        }

        $paiementMensuelModel = new \App\Models\PaiementMensuelModel();
        $echeances = [];

        $dateDebut = new \DateTime($contrat['date_debut']);
        $jourPaiement = $contrat['jour_paiement'];

        for ($i = 0; $i < $nombreMois; $i++) {
            $moisAnnee = $dateDebut->format('Y-m');
            $dateEcheance = $dateDebut->format('Y-' . str_pad($jourPaiement, 2, '0', STR_PAD_LEFT));

            // Vérifier si l'échéance existe déjà
            $echeanceExistante = $paiementMensuelModel
                ->where('contrat_id', $contratId)
                ->where('mois_annee', $moisAnnee)
                ->first();

            if (!$echeanceExistante) {
                $echeances[] = [
                    'contrat_id' => $contratId,
                    'mois_annee' => $moisAnnee,
                    'montant_du' => $contrat['loyer_mensuel'],
                    'date_echeance' => $dateEcheance,
                    'statut' => 'en_attente'
                ];
            }

            $dateDebut->add(new \DateInterval('P1M'));
        }

        if (!empty($echeances)) {
            return $paiementMensuelModel->insertBatch($echeances);
        }

        return true;
    }

    /**
     * Calculer le montant total des paiements en retard
     */
    public function calculerMontantRetard($contratId)
    {
        $paiementMensuelModel = new \App\Models\PaiementMensuelModel();
        
        $retards = $paiementMensuelModel
            ->where('contrat_id', $contratId)
            ->where('statut', 'en_retard')
            ->findAll();

        $totalRetard = 0;
        foreach ($retards as $retard) {
            $totalRetard += ($retard['montant_du'] - $retard['montant_paye']);
        }

        return $totalRetard;
    }
}
