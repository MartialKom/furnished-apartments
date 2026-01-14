<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PaiementModel;
use App\Models\ReservationModel;
use App\Models\LocataireModel;
use App\Models\AppartementModel;
use App\Models\StructureParamModel;
use App\Libraries\PdfGenerator;
use CodeIgniter\HTTP\ResponseInterface;

class PaiementController extends BaseController
{
    protected $paiementModel;
    protected $reservationModel;
    protected $locataireModel;
    protected $appartementModel;
    protected $structureParamModel;

    public function __construct()
    {
        $this->paiementModel = new PaiementModel();
        $this->reservationModel = new ReservationModel();
        $this->locataireModel = new LocataireModel();
        $this->appartementModel = new AppartementModel();
        $this->structureParamModel = new StructureParamModel();
    }

    public function index()
    {
        // Récupérer tous les paiements avec les informations de la réservation et du locataire
        $paiements = $this->paiementModel
            ->select('paiements.*, locataires.nom as locataire_nom, locataires.email, reservations.date_debut, reservations.date_fin, appartements.adresse')
            ->join('locataires', 'locataires.id = paiements.locataire_id')
            ->join('reservations', 'reservations.id = paiements.reservation_id')
            ->join('appartements', 'appartements.id = reservations.appartement_id')
            ->orderBy('paiements.created_at', 'DESC')
            ->findAll();

        // Organiser les paiements par statut
        $paiementsParStatut = [
            'en_attente' => [],
            'paye' => [],
            'rembourse' => [],
            'annule' => []
        ];

        foreach ($paiements as $paiement) {
            $paiementsParStatut[$paiement['statut']][] = $paiement;
        }

        $data = [
            'title' => 'Gestion des Paiements',
            'page_title' => 'Paiements',
            'breadcrumbs' => '<li class="breadcrumb-item">Gestion</li><li class="breadcrumb-item active">Paiements</li>',
            'paiements' => $paiementsParStatut
        ];

        return view('admin/pages/paiements/index', $data);
    }

    public function updateStatut($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Paiement non trouvé.'
            ]);
        }

        $paiement = $this->paiementModel->find($id);
        if (!$paiement) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Paiement non trouvé.'
            ]);
        }

        $nouveauStatut = $this->request->getPost('statut');
        $statutsValides = ['en_attente', 'paye', 'rembourse', 'annule'];

        if (!in_array($nouveauStatut, $statutsValides)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Statut invalide.'
            ]);
        }

        if ($this->paiementModel->update($id, ['statut' => $nouveauStatut])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Statut du paiement mis à jour avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut.'
            ]);
        }
    }

    public function genererFacture($id = null)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'Paiement non trouvé.');
        }

        // Récupérer le paiement
        $paiement = $this->paiementModel->find($id);
        if (!$paiement) {
            return redirect()->back()->with('error', 'Paiement non trouvé.');
        }

        // Récupérer la réservation
        $reservation = $this->reservationModel->find($paiement['reservation_id']);
        if (!$reservation) {
            return redirect()->back()->with('error', 'Réservation non trouvée.');
        }

        // Récupérer le locataire (peut être null pour les nouveaux clients)
        $locataire = null;
        if (!empty($paiement['locataire_id'])) {
            $locataire = $this->locataireModel->find($paiement['locataire_id']);
        }

        // Déterminer les informations client (locataire existant ou nouveau client)
        $clientNom = $locataire ? $locataire['nom'] : ($reservation['client_nom'] ?? 'Client');
        $clientAdresse = $locataire ? ($locataire['adresse'] ?? '') : '';
        $clientTelephone = $locataire ? $locataire['telephone'] : ($reservation['client_telephone'] ?? '');
        $clientEmail = $locataire ? $locataire['email'] : ($reservation['client_email'] ?? '');

        // Récupérer l'appartement
        $appartement = $this->appartementModel->find($reservation['appartement_id']);
        if (!$appartement) {
            return redirect()->back()->with('error', 'Bien non trouvé.');
        }

        // Récupérer les paramètres de structure
        $structureParams = $this->structureParamModel->getStructureParams();

        // Calculer la durée du séjour
        $dateArrivee = new \DateTime($reservation['date_debut']);
        $dateDepart = new \DateTime($reservation['date_fin']);
        $dureeSejour = $dateArrivee->diff($dateDepart)->days;

        // Calculer les montants
        $montantTotal = $reservation['montant_total'];
        $reduction = isset($reservation['reduction_pourcentage']) ? ($montantTotal * $reservation['reduction_pourcentage'] / 100) : 0;
        $montantPaye = $paiement['montant']; // Le champ correct est 'montant', pas 'montant_paye'
        $montantApresReduction = $montantTotal - $reduction;

        // Calculer le montant total payé pour cette réservation
        $totalPayePourReservation = $this->paiementModel
            ->where('reservation_id', $reservation['id'])
            ->where('statut', 'paye')
            ->selectSum('montant', 'total')
            ->first();
        $totalPaye = $totalPayePourReservation['total'] ?? 0;

        $resteAPayer = $montantApresReduction - $totalPaye;

        // Préparer les données pour la facture
        $data = [
            'numero_facture' => 'FACT-' . date('Ymd') . '-' . str_pad($id, 6, '0', STR_PAD_LEFT),
            'date_emission' => date('Y-m-d'),

            // Informations structure
            'structure_nom' => $structureParams['structure_name'],
            'structure_adresse' => $structureParams['structure_address'],
            'structure_telephone' => $structureParams['structure_phone'],
            'structure_email' => $structureParams['structure_email'],
            'structure_logo' => $structureParams['structure_logo'] ?? '',
            'structure_rc' => $structureParams['structure_rc'] ?? '',
            'structure_nif' => $structureParams['structure_nif'] ?? '',

            // Informations client
            'client_nom' => $clientNom,
            'client_adresse' => $clientAdresse,
            'client_telephone' => $clientTelephone,
            'client_email' => $clientEmail,

            // Informations bien
            'bien_adresse' => $appartement['adresse'],
            'bien_type' => $appartement['type'] ?? 'meuble',

            // Informations réservation
            'date_arrivee' => $reservation['date_debut'],
            'date_depart' => $reservation['date_fin'],
            'duree_sejour' => $dureeSejour,
            'statut_reservation' => $reservation['statut'],

            // Montants
            'montant_total' => $montantTotal,
            'reduction' => $reduction,
            'montant_paye' => $montantPaye,
            'reste_a_payer' => $resteAPayer
        ];

        return view('admin/paiements/facture_template', $data);
    }

    /**
     * Télécharger la facture en PDF
     */
    public function downloadFacturePDF($id, $mode = 'D')
    {
        if (!$id) {
            return redirect()->back()->with('error', 'Paiement non trouvé.');
        }

        // Récupérer le paiement
        $paiement = $this->paiementModel->find($id);
        if (!$paiement) {
            return redirect()->back()->with('error', 'Paiement non trouvé.');
        }

        // Récupérer la réservation
        $reservation = $this->reservationModel->find($paiement['reservation_id']);
        if (!$reservation) {
            return redirect()->back()->with('error', 'Réservation non trouvée.');
        }

        // Récupérer le locataire (peut être null pour les nouveaux clients)
        $locataire = null;
        if (!empty($paiement['locataire_id'])) {
            $locataire = $this->locataireModel->find($paiement['locataire_id']);
        }

        // Déterminer les informations client (locataire existant ou nouveau client)
        $clientNom = $locataire ? $locataire['nom'] : ($reservation['client_nom'] ?? 'Client');
        $clientAdresse = $locataire ? ($locataire['adresse'] ?? '') : '';
        $clientTelephone = $locataire ? $locataire['telephone'] : ($reservation['client_telephone'] ?? '');
        $clientEmail = $locataire ? $locataire['email'] : ($reservation['client_email'] ?? '');

        // Récupérer l'appartement
        $appartement = $this->appartementModel->find($reservation['appartement_id']);
        if (!$appartement) {
            return redirect()->back()->with('error', 'Bien non trouvé.');
        }

        // Récupérer les paramètres de structure
        $structureParams = $this->structureParamModel->getStructureParams();

        // Calculer la durée du séjour
        $dateArrivee = new \DateTime($reservation['date_debut']);
        $dateDepart = new \DateTime($reservation['date_fin']);
        $dureeSejour = $dateArrivee->diff($dateDepart)->days;

        // Calculer les montants
        $montantTotal = $reservation['montant_total'];
        $reduction = isset($reservation['reduction_pourcentage']) ? ($montantTotal * $reservation['reduction_pourcentage'] / 100) : 0;
        $montantPaye = $paiement['montant']; // Le champ correct est 'montant', pas 'montant_paye'
        $montantApresReduction = $montantTotal - $reduction;

        // Calculer le montant total payé pour cette réservation
        $totalPayePourReservation = $this->paiementModel
            ->where('reservation_id', $reservation['id'])
            ->where('statut', 'paye')
            ->selectSum('montant', 'total')
            ->first();
        $totalPaye = $totalPayePourReservation['total'] ?? 0;

        $resteAPayer = $montantApresReduction - $totalPaye;

        // Préparer les données pour la facture
        $data = [
            'numero_facture' => 'FACT-' . date('Ymd') . '-' . str_pad($id, 6, '0', STR_PAD_LEFT),
            'date_emission' => date('Y-m-d'),

            // Informations structure
            'structure_nom' => $structureParams['structure_name'],
            'structure_adresse' => $structureParams['structure_address'],
            'structure_telephone' => $structureParams['structure_phone'],
            'structure_email' => $structureParams['structure_email'],
            'structure_logo' => $structureParams['structure_logo'] ?? '',
            'structure_rc' => $structureParams['structure_rc'] ?? '',
            'structure_nif' => $structureParams['structure_nif'] ?? '',

            // Informations client
            'client_nom' => $clientNom,
            'client_adresse' => $clientAdresse,
            'client_telephone' => $clientTelephone,
            'client_email' => $clientEmail,

            // Informations bien
            'bien_adresse' => $appartement['adresse'],
            'bien_type' => $appartement['type'] ?? 'meuble',

            // Informations réservation
            'date_arrivee' => $reservation['date_debut'],
            'date_depart' => $reservation['date_fin'],
            'duree_sejour' => $dureeSejour,
            'statut_reservation' => $reservation['statut'],

            // Montants
            'montant_total' => $montantTotal,
            'reduction' => $reduction,
            'montant_paye' => $montantPaye,
            'reste_a_payer' => $resteAPayer,

            // Flag pour désactiver le script d'auto-impression lors de la génération PDF
            'is_pdf_generation' => true
        ];

        // Générer le PDF
        $pdfGenerator = new PdfGenerator();
        $filename = 'Facture_' . $data['numero_facture'] . '.pdf';

        return $pdfGenerator->generate('admin/paiements/facture_template', $data, $filename, $mode);
    }

    public function get($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Paiement non trouvé.'
            ]);
        }

        $paiement = $this->paiementModel
            ->select('paiements.*, locataires.nom as locataire_nom, locataires.email, reservations.date_debut, reservations.date_fin, appartements.adresse')
            ->join('locataires', 'locataires.id = paiements.locataire_id')
            ->join('reservations', 'reservations.id = paiements.reservation_id')
            ->join('appartements', 'appartements.id = reservations.appartement_id')
            ->find($id);

        if (!$paiement) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Paiement non trouvé.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'paiement' => $paiement
        ]);
    }
}
