<?php

namespace App\Models;

use CodeIgniter\Model;

class RapportModel extends Model
{
    protected $table            = 'rapports';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['utilisateur_id', 'date_rapport', 'contenu', 'document_path', 'document_nom_original'];

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
        'utilisateur_id' => 'required|integer',
        'date_rapport' => 'required|valid_date',
        'contenu' => 'required|string|min_length[10]',
    ];
    protected $validationMessages   = [
        'contenu' => [
            'min_length' => 'Le rapport doit contenir au moins 10 caractères.'
        ]
    ];
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
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Récupérer tous les rapports avec les informations de l'utilisateur
     * Triés du plus récent au plus ancien
     */
    public function getRapportsWithUser()
    {
        return $this->select('rapports.*, utilisateurs.nom, utilisateurs.prenom')
                    ->join('utilisateurs', 'utilisateurs.id = rapports.utilisateur_id')
                    ->orderBy('rapports.date_rapport', 'DESC')
                    ->orderBy('rapports.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Récupérer un rapport avec les informations de l'utilisateur
     */
    public function getRapportWithUser($id)
    {
        return $this->select('rapports.*, utilisateurs.nom, utilisateurs.prenom')
                    ->join('utilisateurs', 'utilisateurs.id = rapports.utilisateur_id')
                    ->where('rapports.id', $id)
                    ->first();
    }

    /**
     * Récupérer les rapports d'un utilisateur spécifique
     */
    public function getRapportsByUser($userId)
    {
        return $this->where('utilisateur_id', $userId)
                    ->orderBy('date_rapport', 'DESC')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Calculer le Cash-Out (dépenses approvisionnement) par mois
     * @param int $nombreMois Nombre de mois à récupérer (défaut: 12)
     * @return array
     */
    public function getCashOutMensuel($nombreMois = 12)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('stock_approvisionnements');

        $dateDebut = date('Y-m-d', strtotime("-$nombreMois months"));

        $result = $builder->select("DATE_FORMAT(date_approvisionnement, '%Y-%m') as mois, SUM(prix_total) as total")
                         ->where('date_approvisionnement >=', $dateDebut)
                         ->groupBy('mois')
                         ->orderBy('mois', 'ASC')
                         ->get()
                         ->getResultArray();

        return $result;
    }

    /**
     * Calculer le Cash-In (revenus) par mois incluant réservations et loyers
     * @param int $nombreMois Nombre de mois à récupérer (défaut: 12)
     * @return array
     */
    public function getCashInMensuel($nombreMois = 12)
    {
        $db = \Config\Database::connect();
        $dateDebut = date('Y-m-d', strtotime("-$nombreMois months"));

        // Récupérer les paiements de réservations
        $builderReservations = $db->table('paiements');
        $reservations = $builderReservations->select("DATE_FORMAT(date, '%Y-%m') as mois, SUM(montant) as total")
                                           ->where('date >=', $dateDebut)
                                           ->where('statut', 'paye')
                                           ->groupBy('mois')
                                           ->get()
                                           ->getResultArray();

        // Récupérer les paiements mensuels (loyers)
        $builderLoyers = $db->table('paiements_mensuels');
        $loyers = $builderLoyers->select("DATE_FORMAT(date_paiement, '%Y-%m') as mois, SUM(montant_paye) as total")
                               ->where('date_paiement >=', $dateDebut)
                               ->where('statut', 'paye')
                               ->groupBy('mois')
                               ->get()
                               ->getResultArray();

        // Fusionner les deux sources de revenus
        $cashIn = [];

        // Ajouter les réservations
        foreach ($reservations as $res) {
            $cashIn[$res['mois']] = [
                'mois' => $res['mois'],
                'reservations' => $res['total'],
                'loyers' => 0,
                'total' => $res['total']
            ];
        }

        // Ajouter les loyers
        foreach ($loyers as $loyer) {
            if (isset($cashIn[$loyer['mois']])) {
                $cashIn[$loyer['mois']]['loyers'] = $loyer['total'];
                $cashIn[$loyer['mois']]['total'] += $loyer['total'];
            } else {
                $cashIn[$loyer['mois']] = [
                    'mois' => $loyer['mois'],
                    'reservations' => 0,
                    'loyers' => $loyer['total'],
                    'total' => $loyer['total']
                ];
            }
        }

        // Trier par mois
        ksort($cashIn);
        return array_values($cashIn);
    }

    /**
     * Calculer le résumé financier (Cash-In vs Cash-Out)
     * @param int $nombreMois
     * @return array
     */
    public function getResumeCashFlow($nombreMois = 12)
    {
        $cashOut = $this->getCashOutMensuel($nombreMois);
        $cashIn = $this->getCashInMensuel($nombreMois);

        // Créer un tableau de tous les mois
        $dateDebut = new \DateTime(date('Y-m-d', strtotime("-$nombreMois months")));
        $dateFin = new \DateTime();

        $mois = [];
        while ($dateDebut <= $dateFin) {
            $moisKey = $dateDebut->format('Y-m');
            $mois[$moisKey] = [
                'mois' => $moisKey,
                'cash_in' => 0,
                'cash_in_reservations' => 0,
                'cash_in_loyers' => 0,
                'cash_out' => 0,
                'balance' => 0
            ];
            $dateDebut->modify('+1 month');
        }

        // Remplir les cash-in
        foreach ($cashIn as $ci) {
            if (isset($mois[$ci['mois']])) {
                $mois[$ci['mois']]['cash_in'] = $ci['total'];
                $mois[$ci['mois']]['cash_in_reservations'] = $ci['reservations'];
                $mois[$ci['mois']]['cash_in_loyers'] = $ci['loyers'];
            }
        }

        // Remplir les cash-out
        foreach ($cashOut as $co) {
            if (isset($mois[$co['mois']])) {
                $mois[$co['mois']]['cash_out'] = $co['total'];
            }
        }

        // Calculer la balance pour chaque mois
        foreach ($mois as $key => $data) {
            $mois[$key]['balance'] = $data['cash_in'] - $data['cash_out'];
        }

        return array_values($mois);
    }

    /**
     * Calculer les totaux pour une période
     * @return array
     */
    public function getTotauxPeriode($nombreMois = 12)
    {
        $cashFlow = $this->getResumeCashFlow($nombreMois);

        $totalCashIn = 0;
        $totalCashOut = 0;
        $totalReservations = 0;
        $totalLoyers = 0;

        foreach ($cashFlow as $data) {
            $totalCashIn += $data['cash_in'];
            $totalCashOut += $data['cash_out'];
            $totalReservations += $data['cash_in_reservations'];
            $totalLoyers += $data['cash_in_loyers'];
        }

        return [
            'total_cash_in' => $totalCashIn,
            'total_cash_out' => $totalCashOut,
            'total_reservations' => $totalReservations,
            'total_loyers' => $totalLoyers,
            'balance_totale' => $totalCashIn - $totalCashOut,
            'periode_mois' => $nombreMois
        ];
    }
}
