<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table            = 'reservations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['date_debut', 'date_fin', 'locataire_id', 'appartement_id', 'statut', 'montant_total', 'montant_paye', 'montant_restant', 'type_reservation', 'notes', 'reduction_pourcentage', 'montant_reduction', 'prix_original', 'motif_annulation'];

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
        'date_debut' => 'required|valid_date',
        'date_fin' => 'required|valid_date',
        'locataire_id' => 'required|integer',
        'appartement_id' => 'required|integer',
        'statut' => 'required|in_list[en_attente,confirmee,annulee,terminee]',
        'montant_total' => 'required|decimal',
        'montant_paye' => 'permit_empty|decimal',
        'montant_restant' => 'permit_empty|decimal',
        'type_reservation' => 'required|in_list[en_ligne,telephonique]',
        'notes' => 'permit_empty|string',
        'reduction_pourcentage' => 'permit_empty|decimal',
        'montant_reduction' => 'permit_empty|decimal',
        'prix_original' => 'permit_empty|decimal',
        'motif_annulation' => 'permit_empty|string'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function creerReservation($data)
    {
        return $this->insert($data);
    }

    public function annulerReservation($id)
    {
        return $this->update($id, ['statut' => 'annulee']);
    }

    public function verifierDisponibilite($appartementId, $dateDebut, $dateFin)
    {
        $conflits = $this->where('appartement_id', $appartementId)
                         ->where('statut !=', 'annulee')
                         ->groupStart()
                             ->where('date_debut <=', $dateFin)
                             ->where('date_fin >=', $dateDebut)
                         ->groupEnd()
                         ->findAll();
        
        return empty($conflits);
    }

    public function getReservationsWithDetails()
    {
        return $this->select('reservations.*, locataires.nom as locataire_nom, locataires.email, appartements.adresse')
                    ->join('locataires', 'locataires.id = reservations.locataire_id')
                    ->join('appartements', 'appartements.id = reservations.appartement_id')
                    ->findAll();
    }

    public function calculerMontantRestant($reservationId)
    {
        $reservation = $this->find($reservationId);
        if (!$reservation) {
            return false;
        }

        // Calculer le total des paiements effectués
        $paiementModel = new \App\Models\PaiementModel();
        $paiementsTotal = $paiementModel->select('SUM(montant) as total')
                                       ->where('reservation_id', $reservationId)
                                       ->where('statut', 'paye')
                                       ->first();

        $montantPaye = $paiementsTotal['total'] ?? 0;
        $montantRestant = $reservation['montant_total'] - $montantPaye;

        // Mettre à jour la réservation
        $this->update($reservationId, [
            'montant_paye' => $montantPaye,
            'montant_restant' => $montantRestant
        ]);

        return [
            'montant_paye' => $montantPaye,
            'montant_restant' => $montantRestant
        ];
    }

    public function creerReservationManuelle($data)
    {
        // Calculer automatiquement le montant restant
        $data['montant_restant'] = $data['montant_total'] - ($data['montant_paye'] ?? 0);
        
        return $this->insert($data);
    }

    public function calculerPrixTotal($appartementId, $dateDebut, $dateFin, $reductionPourcentage = 0)
    {
        $appartementModel = new \App\Models\AppartementModel();
        $appartement = $appartementModel->find($appartementId);
        
        if (!$appartement) {
            return false;
        }

        // Calculer le nombre de jours
        $debut = new \DateTime($dateDebut);
        $fin = new \DateTime($dateFin);
        $nombreJours = $debut->diff($fin)->days;

        // Prix original (prix par nuit * nombre de jours)
        $prixOriginal = $appartement['tarifs'] * $nombreJours;
        
        // Calculer la réduction
        $montantReduction = ($prixOriginal * $reductionPourcentage) / 100;
        $prixFinal = $prixOriginal - $montantReduction;

        return [
            'prix_original' => $prixOriginal,
            'montant_reduction' => $montantReduction,
            'montant_total' => $prixFinal,
            'nombre_jours' => $nombreJours,
            'prix_nuit' => $appartement['tarifs']
        ];
    }

    public function getReservationsWithClientInfo()
    {
        return $this->select('reservations.*, locataires.nom, locataires.email, locataires.telephone, appartements.adresse, appartements.tarifs')
                    ->join('locataires', 'locataires.id = reservations.locataire_id')
                    ->join('appartements', 'appartements.id = reservations.appartement_id')
                    ->orderBy('reservations.created_at', 'DESC')
                    ->findAll();
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
