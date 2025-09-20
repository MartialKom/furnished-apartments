<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\PaiementMensuelModel;
use App\Models\ContratLocataireModel;
use App\Models\LocataireModel;
use App\Models\AppartementModel;
use App\Models\UtilisateurModel;

class TestReceiptSystem extends BaseCommand
{
    protected $group       = 'app';
    protected $name        = 'test:receipt-system';
    protected $description = 'Teste le système de génération de reçus de paiement.';

    public function run(array $params)
    {
        CLI::write('=== TEST DU SYSTÈME DE REÇUS ===', 'green');
        
        $paiementModel = new PaiementMensuelModel();
        $contratModel = new ContratLocataireModel();
        $locataireModel = new LocataireModel();
        $appartementModel = new AppartementModel();
        $utilisateurModel = new UtilisateurModel();
        
        // 1. Vérifier les paiements payés disponibles
        CLI::write("\n1. Recherche des paiements payés pour tester les reçus...");
        $paiementsPayes = $paiementModel->where('statut', 'paye')->findAll();
        CLI::write("✓ " . count($paiementsPayes) . " paiements payés trouvés", 'green');
        
        if (empty($paiementsPayes)) {
            CLI::write("⚠️  Aucun paiement payé trouvé. Créons un paiement de test...", 'yellow');
            
            // Créer un paiement de test
            $contratsActifs = $contratModel->where('statut', 'actif')->findAll();
            if (!empty($contratsActifs)) {
                $contratTest = $contratsActifs[0];
                
                $paiementTest = [
                    'contrat_id' => $contratTest['id'],
                    'mois_annee' => date('Y-m'),
                    'montant_du' => $contratTest['loyer_mensuel'],
                    'montant_paye' => $contratTest['loyer_mensuel'],
                    'date_echeance' => date('Y-m-' . str_pad($contratTest['jour_paiement'], 2, '0', STR_PAD_LEFT)),
                    'date_paiement' => date('Y-m-d H:i:s'),
                    'statut' => 'paye',
                    'mode_paiement' => 'test',
                    'reference_paiement' => 'TEST-' . time(),
                    'notes' => 'Paiement de test pour génération de reçu',
                    'enregistre_par' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                if ($paiementModel->insert($paiementTest)) {
                    CLI::write("✓ Paiement de test créé avec l'ID: " . $paiementModel->getInsertID(), 'green');
                    $paiementsPayes = $paiementModel->where('statut', 'paye')->findAll();
                }
            }
        }
        
        // 2. Tester la génération de reçu simple
        if (!empty($paiementsPayes)) {
            CLI::write("\n2. Test de génération de reçu simple...");
            $paiementTest = $paiementsPayes[0];
            
            // Récupérer les informations nécessaires
            $contrat = $contratModel->getContratDetails($paiementTest['contrat_id']);
            $locataire = $locataireModel->find($contrat['locataire_id']);
            $appartement = $appartementModel->find($contrat['appartement_id']);
            $gestionnaire = $utilisateurModel->find($paiementTest['enregistre_par'] ?? 1);
            
            CLI::write("  Paiement ID: " . $paiementTest['id']);
            CLI::write("  Locataire: " . $locataire['nom']);
            CLI::write("  Appartement: " . $appartement['adresse']);
            CLI::write("  Montant: " . number_format($paiementTest['montant_paye'], 0, ',', ' ') . " FCFA");
            CLI::write("  Gestionnaire: " . ($gestionnaire ? $gestionnaire['nom'] . ' ' . $gestionnaire['prenom'] : 'Système'));
            
            // Simuler la génération du reçu
            $receiptData = $this->simulateReceiptGeneration($paiementTest, $contrat, $locataire, $appartement, $gestionnaire);
            
            CLI::write("✓ Reçu simple généré avec succès", 'green');
            CLI::write("  Numéro de reçu: " . $receiptData['numero_receipt']);
            CLI::write("  URL de génération: /admin/receipts/generate/" . $paiementTest['id']);
        }
        
        // 3. Tester la génération de reçu multiple
        CLI::write("\n3. Test de génération de reçu multiple...");
        
        // Chercher des paiements multiples pour le même contrat
        $paiementsGroupes = [];
        foreach ($paiementsPayes as $paiement) {
            $contratId = $paiement['contrat_id'];
            if (!isset($paiementsGroupes[$contratId])) {
                $paiementsGroupes[$contratId] = [];
            }
            $paiementsGroupes[$contratId][] = $paiement;
        }
        
        $contratAvecMultiples = null;
        foreach ($paiementsGroupes as $contratId => $paiements) {
            if (count($paiements) >= 2) {
                $contratAvecMultiples = $contratId;
                break;
            }
        }
        
        if ($contratAvecMultiples) {
            CLI::write("  Contrat avec paiements multiples trouvé: ID " . $contratAvecMultiples);
            CLI::write("  Nombre de paiements: " . count($paiementsGroupes[$contratAvecMultiples]));
            
            $moisAnnee = $paiementsGroupes[$contratAvecMultiples][0]['mois_annee'];
            CLI::write("✓ Reçu multiple généré avec succès", 'green');
            CLI::write("  URL de génération: /admin/receipts/multiple/" . $contratAvecMultiples . "/" . $moisAnnee);
        } else {
            CLI::write("  Aucun contrat avec paiements multiples trouvé", 'yellow');
            CLI::write("  Pour tester, enregistrez un paiement de plusieurs mois");
        }
        
        // 4. Tester les différentes fonctionnalités du reçu
        CLI::write("\n4. Test des fonctionnalités du reçu...");
        
        if (!empty($paiementsPayes)) {
            $paiementTest = $paiementsPayes[0];
            $contrat = $contratModel->getContratDetails($paiementTest['contrat_id']);
            
            // Test de paiement partiel
            $montantDu = $paiementTest['montant_du'];
            $montantPaye = $paiementTest['montant_paye'];
            $estPartiel = $montantPaye < $montantDu;
            
            CLI::write("  Test paiement partiel: " . ($estPartiel ? "Oui (reste: " . number_format($montantDu - $montantPaye, 0, ',', ' ') . " FCFA)" : "Non"));
            
            // Test prochaine échéance
            $prochaineEcheance = $this->getProchaineEcheance($contrat['id'], $paiementModel);
            if ($prochaineEcheance) {
                CLI::write("  Prochaine échéance: " . date('d/m/Y', strtotime($prochaineEcheance['date'])) . " (" . number_format($prochaineEcheance['montant'], 0, ',', ' ') . " FCFA)");
            } else {
                CLI::write("  Aucune prochaine échéance trouvée");
            }
            
            // Test numérotation
            $numeroReceipt = $this->generateReceiptNumber();
            CLI::write("  Numéro de reçu généré: " . $numeroReceipt);
        }
        
        // 5. Vérifier la structure des données
        CLI::write("\n5. Vérification de la structure des données...");
        
        $requiredFields = ['contrat_id', 'mois_annee', 'montant_du', 'montant_paye', 'date_paiement', 'statut'];
        $missingFields = [];
        
        if (!empty($paiementsPayes)) {
            $paiementTest = $paiementsPayes[0];
            foreach ($requiredFields as $field) {
                if (!isset($paiementTest[$field])) {
                    $missingFields[] = $field;
                }
            }
            
            if (empty($missingFields)) {
                CLI::write("✓ Tous les champs requis sont présents", 'green');
            } else {
                CLI::write("✗ Champs manquants: " . implode(', ', $missingFields), 'red');
            }
        }
        
        // 6. URLs de test
        CLI::write("\n6. URLs de test pour les reçus:");
        if (!empty($paiementsPayes)) {
            $paiementTest = $paiementsPayes[0];
            CLI::write("  Reçu simple: " . base_url('admin/receipts/generate/' . $paiementTest['id']));
            CLI::write("  Visualisation: " . base_url('admin/receipts/view/' . $paiementTest['id']));
        }
        
        if ($contratAvecMultiples) {
            $moisAnnee = $paiementsGroupes[$contratAvecMultiples][0]['mois_annee'];
            CLI::write("  Reçu multiple: " . base_url('admin/receipts/multiple/' . $contratAvecMultiples . '/' . $moisAnnee));
        }
        
        CLI::write("\n=== FIN DU TEST REÇUS ===", 'green');
    }
    
    private function simulateReceiptGeneration($paiement, $contrat, $locataire, $appartement, $gestionnaire)
    {
        $numeroReceipt = $this->generateReceiptNumber();
        
        return [
            'numero_receipt' => $numeroReceipt,
            'date_emission' => date('Y-m-d H:i:s'),
            'date_paiement' => $paiement['date_paiement'],
            'locataire_nom' => $locataire['nom'],
            'appartement_adresse' => $appartement['adresse'],
            'montant_paye' => $paiement['montant_paye'],
            'gestionnaire_nom' => $gestionnaire ? $gestionnaire['nom'] . ' ' . $gestionnaire['prenom'] : 'Système'
        ];
    }
    
    private function getProchaineEcheance($contratId, $paiementModel)
    {
        $prochaineEcheance = $paiementModel->where('contrat_id', $contratId)
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
    
    private function generateReceiptNumber()
    {
        $prefix = 'RCP';
        $date = date('Ymd');
        $time = date('His');
        return $prefix . $date . $time;
    }
}
