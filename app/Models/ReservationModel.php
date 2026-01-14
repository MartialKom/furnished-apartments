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
    protected $allowedFields    = [
        'date_debut',
        'date_fin',
        'locataire_id',
        'appartement_id',
        'statut',
        'montant_total',
        'montant_paye',
        'montant_restant',
        'mode_paiement',
        'type_reservation',
        'notes',
        'reduction_pourcentage',
        'montant_reduction',
        'prix_original',
        'motif_annulation',
        'client_nom',
        'client_email',
        'client_telephone'
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
        'date_debut' => 'required|valid_date',
        'date_fin' => 'required|valid_date',
        'locataire_id' => 'permit_empty|integer',
        'appartement_id' => 'required|integer',
        'statut' => 'required|in_list[en_attente,confirmee,en_cours,terminee,annulee]',
        'montant_total' => 'permit_empty|decimal',
        'montant_paye' => 'permit_empty|decimal',
        'montant_restant' => 'permit_empty|decimal',
        'mode_paiement' => 'required|in_list[especes,orange_money,momo,carte_bancaire,virement]',
        'type_reservation' => 'permit_empty|in_list[en_ligne,telephonique,presentiel]',
        'notes' => 'permit_empty|string',
        'reduction_pourcentage' => 'permit_empty|decimal',
        'montant_reduction' => 'permit_empty|decimal',
        'prix_original' => 'permit_empty|decimal',
        'motif_annulation' => 'permit_empty|string',
        'client_nom' => 'permit_empty|string|max_length[255]',
        'client_email' => 'permit_empty|valid_email|max_length[255]',
        'client_telephone' => 'permit_empty|string|max_length[50]'
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
        return $this->select('reservations.*,
                              COALESCE(locataires.nom, reservations.client_nom) as locataire_nom,
                              COALESCE(locataires.email, reservations.client_email) as email,
                              appartements.adresse')
                    ->join('locataires', 'locataires.id = reservations.locataire_id', 'left')
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
        log_message('info', '[ReservationModel] Début creerReservationManuelle');
        log_message('info', '[ReservationModel] Données reçues: ' . json_encode($data));

        // Calculer automatiquement le montant restant
        $data['montant_restant'] = $data['montant_total'] - ($data['montant_paye'] ?? 0);

        log_message('info', '[ReservationModel] Montant restant calculé: ' . $data['montant_restant']);
        log_message('info', '[ReservationModel] Tentative d\'insertion...');

        $result = $this->insert($data);

        if ($result) {
            log_message('info', "[ReservationModel] Insertion réussie - ID: {$result}");
        } else {
            $errors = $this->errors();
            log_message('error', '[ReservationModel] Insertion échouée - Erreurs: ' . json_encode($errors));
            log_message('error', '[ReservationModel] Données qui ont échoué: ' . json_encode($data));
        }

        return $result;
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
        return $this->select('reservations.*,
                              COALESCE(locataires.nom, reservations.client_nom) as nom,
                              COALESCE(locataires.email, reservations.client_email) as email,
                              COALESCE(locataires.telephone, reservations.client_telephone) as telephone,
                              appartements.adresse,
                              appartements.tarifs')
                    ->join('locataires', 'locataires.id = reservations.locataire_id', 'left')
                    ->join('appartements', 'appartements.id = reservations.appartement_id')
                    ->orderBy('reservations.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Met à jour automatiquement les statuts des réservations en fonction des dates
     * - Passe de 'confirmee' à 'en_cours' si la date de début est atteinte
     * - Passe de 'en_cours' à 'terminee' si la date de fin est dépassée
     */
    public function mettreAJourStatutsAutomatiques()
    {
        $dateActuelle = date('Y-m-d');

        // Passer les réservations confirmées en 'en_cours' si la date de début est atteinte ou dépassée
        $this->where('statut', 'confirmee')
             ->where('date_debut <=', $dateActuelle)
             ->set(['statut' => 'en_cours'])
             ->update();

        // Passer les réservations en cours en 'terminee' si la date de fin est dépassée
        $this->where('statut', 'en_cours')
             ->where('date_fin <', $dateActuelle)
             ->set(['statut' => 'terminee'])
             ->update();

        return true;
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
