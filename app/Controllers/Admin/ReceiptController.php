<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PaiementMensuelModel;
use App\Models\ContratLocataireModel;
use App\Models\LocataireModel;
use App\Models\AppartementModel;
use App\Models\UtilisateurModel;
use App\Models\StructureParamModel;

class ReceiptController extends BaseController
{
    protected $paiementModel;
    protected $contratModel;
    protected $locataireModel;
    protected $appartementModel;
    protected $utilisateurModel;
    protected $structureParamModel;

    public function __construct()
    {
        $this->paiementModel = new PaiementMensuelModel();
        $this->contratModel = new ContratLocataireModel();
        $this->locataireModel = new LocataireModel();
        $this->appartementModel = new AppartementModel();
        $this->utilisateurModel = new UtilisateurModel();
        $this->structureParamModel = new StructureParamModel();
    }

    /**
     * Générer un reçu de paiement
     */
    public function generateReceipt($paiementId)
    {
        // Récupérer les informations du paiement
        $paiement = $this->paiementModel->find($paiementId);
        
        if (!$paiement) {
            return redirect()->back()->with('error', 'Paiement introuvable.');
        }

        // Récupérer les informations du contrat
        $contrat = $this->contratModel->getContratDetails($paiement['contrat_id']);
        if (!$contrat) {
            return redirect()->back()->with('error', 'Contrat introuvable.');
        }

        // Récupérer les informations du locataire
        $locataire = $this->locataireModel->find($contrat['locataire_id']);
        if (!$locataire) {
            return redirect()->back()->with('error', 'Locataire introuvable.');
        }

        // Récupérer les informations de l'appartement
        $appartement = $this->appartementModel->find($contrat['appartement_id']);
        if (!$appartement) {
            return redirect()->back()->with('error', 'Appartement introuvable.');
        }

        // Récupérer les informations du gestionnaire qui a encaissé
        $gestionnaire = null;
        if (!empty($paiement['enregistre_par'])) {
            $gestionnaire = $this->utilisateurModel->find($paiement['enregistre_par']);
        }

        // Calculer les informations du reçu
        $receiptData = $this->calculateReceiptData($paiement, $contrat, $locataire, $appartement, $gestionnaire);

        // Générer le PDF
        return $this->generatePDF($receiptData);
    }

    /**
     * Générer un reçu pour un paiement multiple
     */
    public function generateMultipleReceipt($contratId, $moisAnnee)
    {
        // Récupérer le contrat
        $contrat = $this->contratModel->getContratDetails($contratId);
        if (!$contrat) {
            return redirect()->back()->with('error', 'Contrat introuvable.');
        }

        // Récupérer les paiements pour ce mois/année
        $paiements = $this->paiementModel->where('contrat_id', $contratId)
                                        ->where('mois_annee', $moisAnnee)
                                        ->where('statut', 'paye')
                                        ->findAll();

        if (empty($paiements)) {
            return redirect()->back()->with('error', 'Aucun paiement trouvé pour cette période.');
        }

        // Récupérer les informations complètes
        $locataire = $this->locataireModel->find($contrat['locataire_id']);
        $appartement = $this->appartementModel->find($contrat['appartement_id']);
        
        $gestionnaire = null;
        if (!empty($paiements[0]['enregistre_par'])) {
            $gestionnaire = $this->utilisateurModel->find($paiements[0]['enregistre_par']);
        }

        // Calculer les informations du reçu multiple
        $receiptData = $this->calculateMultipleReceiptData($paiements, $contrat, $locataire, $appartement, $gestionnaire);

        // Générer le PDF
        return $this->generatePDF($receiptData);
    }

    /**
     * Calculer les données du reçu simple
     */
    private function calculateReceiptData($paiement, $contrat, $locataire, $appartement, $gestionnaire)
    {
        $montantPaye = $paiement['montant_paye'];
        $montantDu = $paiement['montant_du'];
        $estPartiel = $montantPaye < $montantDu;
        $resteAPayer = $montantDu - $montantPaye;

        // Calculer la prochaine échéance
        $prochaineEcheance = $this->getProchaineEcheance($contrat['id']);

        // Récupérer les paramètres de structure
        $structureParams = $this->structureParamModel->getStructureParams();

        return [
            'type' => 'simple',
            'numero_receipt' => $this->generateReceiptNumber(),
            'date_emission' => date('Y-m-d H:i:s'),
            'date_paiement' => $paiement['date_paiement'],
            
            // Informations structure
            'structure_nom' => $structureParams['structure_name'],
            'structure_adresse' => $structureParams['structure_address'],
            'structure_telephone' => $structureParams['structure_phone'],
            'structure_email' => $structureParams['structure_email'],
            
            // Informations locataire
            'locataire_nom' => $locataire['nom'],
            'locataire_email' => $locataire['email'],
            'locataire_telephone' => $locataire['telephone'],
            
            // Informations appartement
            'appartement_adresse' => $appartement['adresse'],
            'appartement_type' => $appartement['type'] ?? 'meuble',
            'appartement_surface' => 'N/A', // Surface non disponible dans la table actuelle
            
            // Informations paiement
            'montant_du' => $montantDu,
            'montant_paye' => $montantPaye,
            'reste_a_payer' => $resteAPayer,
            'est_paiement_partiel' => $estPartiel,
            'nombre_mensualites' => $paiement['nombre_mois_payes'] ?? 1,
            'mois_annee' => $paiement['mois_annee'],
            'mode_paiement' => $paiement['mode_paiement'],
            'reference_paiement' => $paiement['reference_paiement'],
            'notes' => $paiement['notes'],
            
            // Informations gestionnaire
            'gestionnaire_nom' => $gestionnaire ? $gestionnaire['nom'] . ' ' . $gestionnaire['prenom'] : 'Système',
            'gestionnaire_role' => $gestionnaire ? ucfirst($gestionnaire['role']) : 'Administrateur',
            
            // Prochaine échéance
            'prochaine_echeance_date' => $prochaineEcheance['date'] ?? null,
            'prochaine_echeance_montant' => $prochaineEcheance['montant'] ?? $contrat['loyer_mensuel'],
            
            // Informations contrat
            'loyer_mensuel' => $contrat['loyer_mensuel'],
            'jour_paiement' => $contrat['jour_paiement'],
            'date_debut_contrat' => $contrat['date_debut'],
            'statut_contrat' => $contrat['statut']
        ];
    }

    /**
     * Calculer les données du reçu multiple
     */
    private function calculateMultipleReceiptData($paiements, $contrat, $locataire, $appartement, $gestionnaire)
    {
        $totalPaye = 0;
        $nombreMensualites = count($paiements);
        $moisPayes = [];
        
        foreach ($paiements as $paiement) {
            $totalPaye += $paiement['montant_paye'];
            $moisPayes[] = [
                'mois_annee' => $paiement['mois_annee'],
                'montant' => $paiement['montant_paye'],
                'date_paiement' => $paiement['date_paiement']
            ];
        }

        // Calculer la prochaine échéance
        $prochaineEcheance = $this->getProchaineEcheance($contrat['id']);

        // Récupérer les paramètres de structure
        $structureParams = $this->structureParamModel->getStructureParams();

        return [
            'type' => 'multiple',
            'numero_receipt' => $this->generateReceiptNumber(),
            'date_emission' => date('Y-m-d H:i:s'),
            'date_paiement' => $paiements[0]['date_paiement'],
            
            // Informations structure
            'structure_nom' => $structureParams['structure_name'],
            'structure_adresse' => $structureParams['structure_address'],
            'structure_telephone' => $structureParams['structure_phone'],
            'structure_email' => $structureParams['structure_email'],
            
            // Informations locataire
            'locataire_nom' => $locataire['nom'],
            'locataire_email' => $locataire['email'],
            'locataire_telephone' => $locataire['telephone'],
            
            // Informations appartement
            'appartement_adresse' => $appartement['adresse'],
            'appartement_type' => $appartement['type'] ?? 'meuble',
            'appartement_surface' => 'N/A', // Surface non disponible dans la table actuelle
            
            // Informations paiement multiple
            'montant_total_paye' => $totalPaye,
            'nombre_mensualites' => $nombreMensualites,
            'mois_payes' => $moisPayes,
            'mode_paiement' => $paiements[0]['mode_paiement'],
            'reference_paiement' => $paiements[0]['reference_paiement'],
            'notes' => $paiements[0]['notes'],
            
            // Informations gestionnaire
            'gestionnaire_nom' => $gestionnaire ? $gestionnaire['nom'] . ' ' . $gestionnaire['prenom'] : 'Système',
            'gestionnaire_role' => $gestionnaire ? ucfirst($gestionnaire['role']) : 'Administrateur',
            
            // Prochaine échéance
            'prochaine_echeance_date' => $prochaineEcheance['date'] ?? null,
            'prochaine_echeance_montant' => $prochaineEcheance['montant'] ?? $contrat['loyer_mensuel'],
            
            // Informations contrat
            'loyer_mensuel' => $contrat['loyer_mensuel'],
            'jour_paiement' => $contrat['jour_paiement'],
            'date_debut_contrat' => $contrat['date_debut'],
            'statut_contrat' => $contrat['statut']
        ];
    }

    /**
     * Obtenir la prochaine échéance
     */
    private function getProchaineEcheance($contratId)
    {
        $prochaineEcheance = $this->paiementModel->where('contrat_id', $contratId)
                                                ->where('statut', 'en_attente')
                                                ->where('date_echeance >=', date('Y-m-d'))
                                                ->orderBy('date_echeance', 'ASC')
                                                ->first();
        
        if ($prochaineEcheance) {
            return [
                'date' => $prochaineEcheance['date_echeance'],
                'montant' => $prochaineEcheance['montant_du']
            ];
        }

        return null;
    }

    /**
     * Générer un numéro de reçu unique
     */
    private function generateReceiptNumber()
    {
        $prefix = 'RCP';
        $date = date('Ymd');
        $time = date('His');
        return $prefix . $date . $time;
    }

    /**
     * Générer le PDF du reçu
     */
    private function generatePDF($receiptData)
    {
        // Créer le HTML du reçu
        $html = $this->generateReceiptHTML($receiptData);
        
        // Pour l'instant, retourner une vue HTML (on peut ajouter une librairie PDF plus tard)
        return view('admin/receipts/receipt', $receiptData);
    }

    /**
     * Générer le HTML du reçu
     */
    private function generateReceiptHTML($data)
    {
        // Cette méthode peut être utilisée pour générer du HTML
        // qui peut ensuite être converti en PDF avec une librairie comme DomPDF
        return view('admin/receipts/receipt', $data);
    }

    /**
     * Afficher le reçu dans le navigateur
     */
    public function viewReceipt($paiementId)
    {
        $paiement = $this->paiementModel->find($paiementId);
        
        if (!$paiement) {
            return redirect()->back()->with('error', 'Paiement introuvable.');
        }

        $contrat = $this->contratModel->getContratDetails($paiement['contrat_id']);
        $locataire = $this->locataireModel->find($contrat['locataire_id']);
        $appartement = $this->appartementModel->find($contrat['appartement_id']);
        
        $gestionnaire = null;
        if (!empty($paiement['enregistre_par'])) {
            $gestionnaire = $this->utilisateurModel->find($paiement['enregistre_par']);
        }

        $receiptData = $this->calculateReceiptData($paiement, $contrat, $locataire, $appartement, $gestionnaire);

        return view('admin/receipts/receipt', $receiptData);
    }
}