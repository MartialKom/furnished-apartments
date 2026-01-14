<?php

namespace App\Models;

use CodeIgniter\Model;

class PaiementMensuelModel extends Model
{
    protected $table            = 'paiements_mensuels';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'contrat_id', 'mois_annee', 'montant_du', 'montant_paye', 
        'date_echeance', 'date_paiement', 'statut', 'nombre_mois_payes',
        'mode_paiement', 'reference_paiement', 'notes', 'enregistre_par'
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
        'mois_annee' => 'required|string|max_length[7]',
        'montant_du' => 'required|decimal',
        'montant_paye' => 'required|decimal',
        'date_echeance' => 'required|valid_date',
        'date_paiement' => 'permit_empty|valid_date',
        'statut' => 'required|in_list[en_attente,paye,en_retard,partiellement_paye]',
        'nombre_mois_payes' => 'required|integer|greater_than[0]',
        'mode_paiement' => 'permit_empty|in_list[especes,virement,cheque,mobile_money]',
        'reference_paiement' => 'permit_empty|string|max_length[255]',
        'notes' => 'permit_empty|string',
        'enregistre_par' => 'permit_empty|integer'
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
     * Obtenir les paiements mensuels d'un contrat
     */
    public function getPaiementsByContrat($contratId)
    {
        return $this->select('paiements_mensuels.*, u.nom as enregistre_par_nom')
                    ->join('utilisateurs u', 'u.id = paiements_mensuels.enregistre_par', 'left')
                    ->where('contrat_id', $contratId)
                    ->orderBy('mois_annee', 'DESC')
                    ->findAll();
    }

    /**
     * Obtenir les paiements en retard
     */
    public function getPaiementsEnRetard()
    {
        $aujourdhui = date('Y-m-d');
        
        return $this->select('paiements_mensuels.*, cl.loyer_mensuel, cl.jour_paiement,
                             l.nom as locataire_nom, l.email as locataire_email, l.telephone as locataire_telephone,
                             a.adresse as appartement_adresse')
                    ->join('contrats_locataires cl', 'cl.id = paiements_mensuels.contrat_id')
                    ->join('locataires l', 'l.id = cl.locataire_id')
                    ->join('appartements a', 'a.id = cl.appartement_id')
                    ->where('paiements_mensuels.statut', 'en_retard')
                    ->where('paiements_mensuels.date_echeance <', $aujourdhui)
                    ->findAll();
    }

    /**
     * Obtenir les échéances proches (dans les 5 prochains jours)
     */
    public function getEcheancesProches()
    {
        $aujourdhui = date('Y-m-d');
        $dans5Jours = date('Y-m-d', strtotime('+5 days'));
        
        return $this->select('paiements_mensuels.*, cl.loyer_mensuel, cl.jour_paiement,
                             l.nom as locataire_nom, l.email as locataire_email, l.telephone as locataire_telephone,
                             a.adresse as appartement_adresse')
                    ->join('contrats_locataires cl', 'cl.id = paiements_mensuels.contrat_id')
                    ->join('locataires l', 'l.id = cl.locataire_id')
                    ->join('appartements a', 'a.id = cl.appartement_id')
                    ->where('paiements_mensuels.statut', 'en_attente')
                    ->where('paiements_mensuels.date_echeance >=', $aujourdhui)
                    ->where('paiements_mensuels.date_echeance <=', $dans5Jours)
                    ->findAll();
    }

    /**
     * Enregistrer un paiement mensuel
     */
    public function enregistrerPaiement($contratId, $moisAnnee, $montantPaye, $nombreMois = 1, $modePaiement = null, $reference = null, $notes = null, $enregistrePar = null)
    {
        $this->db->transStart();

        try {
            $contratModel = new \App\Models\ContratLocataireModel();
            $contrat = $contratModel->find($contratId);
            
            if (!$contrat) {
                throw new \Exception('Contrat non trouvé');
            }

            $loyerMensuel = $contrat['loyer_mensuel'];
            $montantTotalDu = $loyerMensuel * $nombreMois;
            
            // Vérifier si le montant payé correspond au nombre de mois
            if ($montantPaye < $montantTotalDu) {
                throw new \Exception("Le montant payé ({$montantPaye} FCFA) ne correspond pas au nombre de mois ({$nombreMois} mois = {$montantTotalDu} FCFA)");
            }

            // Créer ou mettre à jour les échéances pour chaque mois
            $dateDebut = new \DateTime($moisAnnee . '-01');
            
            for ($i = 0; $i < $nombreMois; $i++) {
                $moisCourant = $dateDebut->format('Y-m');
                $dateEcheance = $dateDebut->format('Y-' . str_pad($contrat['jour_paiement'], 2, '0', STR_PAD_LEFT));
                
                // Vérifier si l'échéance existe
                $echeanceExistante = $this->where('contrat_id', $contratId)
                                        ->where('mois_annee', $moisCourant)
                                        ->first();
                
                if ($echeanceExistante) {
                    // Mettre à jour l'échéance existante
                    $this->update($echeanceExistante['id'], [
                        'montant_paye' => $loyerMensuel,
                        'date_paiement' => date('Y-m-d'),
                        'statut' => 'paye',
                        'nombre_mois_payes' => 1,
                        'mode_paiement' => $modePaiement,
                        'reference_paiement' => $reference,
                        'notes' => $notes,
                        'enregistre_par' => $enregistrePar,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    // Créer une nouvelle échéance
                    $this->insert([
                        'contrat_id' => $contratId,
                        'mois_annee' => $moisCourant,
                        'montant_du' => $loyerMensuel,
                        'montant_paye' => $loyerMensuel,
                        'date_echeance' => $dateEcheance,
                        'date_paiement' => date('Y-m-d'),
                        'statut' => 'paye',
                        'nombre_mois_payes' => 1,
                        'mode_paiement' => $modePaiement,
                        'reference_paiement' => $reference,
                        'notes' => $notes,
                        'enregistre_par' => $enregistrePar,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                
                $dateDebut->add(new \DateInterval('P1M'));
            }

            $this->db->transComplete();

            return $this->db->transStatus();
        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    /**
     * Créer les échéances pour les mois suivants
     */
    private function creerEcheancesMoisSuivants($contratId, $moisAnnee, $nombreMois, $montantRestant)
    {
        $contratModel = new \App\Models\ContratLocataireModel();
        $contrat = $contratModel->find($contratId);

        $dateActuelle = new \DateTime($moisAnnee . '-01');
        
        for ($i = 1; $i <= $nombreMois; $i++) {
            $dateActuelle->add(new \DateInterval('P1M'));
            $nouveauMoisAnnee = $dateActuelle->format('Y-m');
            
            $dateEcheance = $dateActuelle->format('Y-' . str_pad($contrat['jour_paiement'], 2, '0', STR_PAD_LEFT));

            // Vérifier si l'échéance existe déjà
            $echeanceExistante = $this->where('contrat_id', $contratId)
                                   ->where('mois_annee', $nouveauMoisAnnee)
                                   ->first();

            if (!$echeanceExistante) {
                $this->insert([
                    'contrat_id' => $contratId,
                    'mois_annee' => $nouveauMoisAnnee,
                    'montant_du' => $contrat['loyer_mensuel'],
                    'date_echeance' => $dateEcheance,
                    'statut' => 'en_attente'
                ]);
            }
        }
    }

    /**
     * Mettre à jour le statut des échéances en retard
     */
    public function mettreAJourStatutsRetard()
    {
        $aujourdhui = date('Y-m-d');
        
        return $this->where('date_echeance <', $aujourdhui)
                    ->where('statut', 'en_attente')
                    ->set('statut', 'en_retard')
                    ->update();
    }

    /**
     * Obtenir le résumé des paiements d'un contrat
     */
    public function getResumeContrat($contratId)
    {
        $query = $this->where('contrat_id', $contratId);
        
        $total = [
            'total_du' => $query->selectSum('montant_du')->first()['montant_du'] ?? 0,
            'total_paye' => $query->selectSum('montant_paye')->first()['montant_paye'] ?? 0,
            'en_attente' => $query->where('statut', 'en_attente')->countAllResults(),
            'en_retard' => $query->where('statut', 'en_retard')->countAllResults(),
            'paye' => $query->where('statut', 'paye')->countAllResults()
        ];

        $total['montant_restant'] = $total['total_du'] - $total['total_paye'];

        return $total;
    }
}
