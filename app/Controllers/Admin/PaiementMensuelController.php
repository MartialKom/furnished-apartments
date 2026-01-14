<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PaiementMensuelModel;
use App\Models\ContratLocataireModel;

class PaiementMensuelController extends BaseController
{
    protected $paiementModel;
    protected $contratModel;

    public function __construct()
    {
        $this->paiementModel = new PaiementMensuelModel();
        $this->contratModel = new ContratLocataireModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Paiements Mensuels',
            'echeances_proches' => $this->paiementModel->getEcheancesProches(),
            'retards' => $this->paiementModel->getPaiementsEnRetard()
        ];
        
        return view('admin/pages/paiements_mensuels/index', $data);
    }

    public function show($id)
    {
        $paiement = $this->paiementModel->find($id);
        
        if (!$paiement) {
            return redirect()->to('/admin/paiements-mensuels')->with('error', 'Paiement non trouvé.');
        }

        $contrat = $this->contratModel->getContratAvecDetails($paiement['contrat_id']);

        $data = [
            'title' => 'Détails du Paiement',
            'paiement' => $paiement,
            'contrat' => $contrat
        ];
        
        return view('admin/pages/paiements_mensuels/show', $data);
    }

    public function enregistrerPaiement()
    {
        $rules = [
            'contrat_id' => 'required|integer',
            'mois_annee' => 'required|string|max_length[7]',
            'montant_paye' => 'required|decimal|greater_than[0]',
            'nombre_mois' => 'required|integer|greater_than[0]',
            'mode_paiement' => 'permit_empty|in_list[especes,virement,cheque,mobile_money]',
            'reference_paiement' => 'permit_empty|string|max_length[255]',
            'notes' => 'permit_empty|string'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $contratId = $this->request->getPost('contrat_id');
        $moisAnnee = $this->request->getPost('mois_annee');
        $montantPaye = $this->request->getPost('montant_paye');
        $nombreMois = $this->request->getPost('nombre_mois');
        $modePaiement = $this->request->getPost('mode_paiement');
        $reference = $this->request->getPost('reference_paiement');
        $notes = $this->request->getPost('notes');
        $enregistrePar = session()->get('user_id');

        try {
            $result = $this->paiementModel->enregistrerPaiement(
                $contratId, $moisAnnee, $montantPaye, $nombreMois, 
                $modePaiement, $reference, $notes, $enregistrePar
            );
            
            if ($result) {
                $message = $nombreMois > 1 
                    ? "Paiement de {$nombreMois} mois enregistré avec succès. Le locataire ne recevra plus de notifications pour ces mois."
                    : "Paiement enregistré avec succès.";
                    
                return $this->response->setJSON([
                    'success' => true,
                    'message' => $message,
                    'details' => [
                        'montant' => $montantPaye,
                        'mois' => $nombreMois,
                        'mois_annee' => $moisAnnee,
                        'contrat_id' => $contratId
                    ]
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de l\'enregistrement du paiement.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getPaiementsContrat($contratId)
    {
        $paiements = $this->paiementModel->getPaiementsByContrat($contratId);
        $resume = $this->paiementModel->getResumeContrat($contratId);
        
        return $this->response->setJSON([
            'success' => true,
            'paiements' => $paiements,
            'resume' => $resume
        ]);
    }

    public function getEcheanceDetails($id)
    {
        $echeance = $this->paiementModel->find($id);
        
        if (!$echeance) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Échéance non trouvée.'
            ]);
        }

        $contrat = $this->contratModel->getContratAvecDetails($echeance['contrat_id']);

        return $this->response->setJSON([
            'success' => true,
            'echeance' => $echeance,
            'contrat' => $contrat
        ]);
    }

    public function envoyerRappel($id)
    {
        $echeance = $this->paiementModel->find($id);

        if (!$echeance) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Échéance non trouvée.'
            ]);
        }

        $contrat = $this->contratModel->getContratAvecDetails($echeance['contrat_id']);

        $locataire = [
            'nom' => $contrat['locataire_nom'],
            'email' => $contrat['locataire_email'],
            'telephone' => $contrat['locataire_telephone']
        ];

        $notificationService = new \App\Libraries\NotificationService();

        if ($echeance['statut'] === 'en_retard') {
            $result = $notificationService->envoyerNotificationRetard($locataire, $echeance, $contrat);
        } else {
            $result = $notificationService->envoyerNotificationEcheance($locataire, $echeance, $contrat);
        }

        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Rappel envoyé avec succès à ' . $locataire['nom']
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi du rappel. Vérifiez la configuration SMTP.'
            ]);
        }
    }

    public function envoyerRappelsGroupes()
    {
        $echeancesProches = $this->paiementModel->getEcheancesProches();

        if (empty($echeancesProches)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Aucune échéance proche à notifier.'
            ]);
        }

        $notificationService = new \App\Libraries\NotificationService();
        $envoyes = 0;
        $erreurs = 0;

        foreach ($echeancesProches as $echeance) {
            $contrat = $this->contratModel->getContratAvecDetails($echeance['contrat_id']);

            $locataire = [
                'nom' => $contrat['locataire_nom'],
                'email' => $contrat['locataire_email'],
                'telephone' => $contrat['locataire_telephone']
            ];

            $result = $notificationService->envoyerNotificationEcheance($locataire, $echeance, $contrat);

            if ($result) {
                $envoyes++;
            } else {
                $erreurs++;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "$envoyes rappel(s) envoyé(s) avec succès. $erreurs erreur(s).",
            'envoyes' => $envoyes,
            'erreurs' => $erreurs
        ]);
    }

    public function envoyerRappelsRetard()
    {
        $retards = $this->paiementModel->getPaiementsEnRetard();

        if (empty($retards)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Aucun paiement en retard.'
            ]);
        }

        $notificationService = new \App\Libraries\NotificationService();
        $envoyes = 0;
        $erreurs = 0;

        foreach ($retards as $retard) {
            $contrat = $this->contratModel->getContratAvecDetails($retard['contrat_id']);

            $locataire = [
                'nom' => $contrat['locataire_nom'],
                'email' => $contrat['locataire_email'],
                'telephone' => $contrat['locataire_telephone']
            ];

            $result = $notificationService->envoyerNotificationRetard($locataire, $retard, $contrat);

            if ($result) {
                $envoyes++;
            } else {
                $erreurs++;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "$envoyes rappel(s) de retard envoyé(s). $erreurs erreur(s).",
            'envoyes' => $envoyes,
            'erreurs' => $erreurs
        ]);
    }

    public function dashboard()
    {
        $data = [
            'title' => 'Tableau de Bord Paiements Mensuels',
            'echeances_proches' => $this->paiementModel->getEcheancesProches(),
            'retards' => $this->paiementModel->getPaiementsEnRetard(),
            'stats' => $this->getStatsPaiements()
        ];

        return view('admin/pages/paiements_mensuels/dashboard', $data);
    }

    private function getStatsPaiements()
    {
        $aujourdhui = date('Y-m-d');
        $debutMois = date('Y-m-01');
        $finMois = date('Y-m-t');

        $stats = [
            'total_du_mois' => $this->paiementModel
                ->where('date_echeance >=', $debutMois)
                ->where('date_echeance <=', $finMois)
                ->selectSum('montant_du')
                ->first()['montant_du'] ?? 0,
            
            'total_paye_mois' => $this->paiementModel
                ->where('date_echeance >=', $debutMois)
                ->where('date_echeance <=', $finMois)
                ->where('statut', 'paye')
                ->selectSum('montant_paye')
                ->first()['montant_paye'] ?? 0,
            
            'en_retard' => $this->paiementModel
                ->where('statut', 'en_retard')
                ->countAllResults(),
            
            'echeances_aujourdhui' => $this->paiementModel
                ->where('date_echeance', $aujourdhui)
                ->where('statut', 'en_attente')
                ->countAllResults()
        ];

        $stats['taux_recouvrement'] = $stats['total_du_mois'] > 0 
            ? round(($stats['total_paye_mois'] / $stats['total_du_mois']) * 100, 2) 
            : 0;

        return $stats;
    }
}
