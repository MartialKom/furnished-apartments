<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\PaiementMensuelModel;
use App\Models\ContratLocataireModel;
use App\Models\LocataireModel;

class TestPaymentSystem extends BaseCommand
{
    protected $group       = 'app';
    protected $name        = 'test:payment-system';
    protected $description = 'Teste le système de paiement mensuel et vérifie les notifications.';

    public function run(array $params)
    {
        CLI::write('=== TEST DU SYSTÈME DE PAIEMENT ===', 'green');
        
        $paiementModel = new PaiementMensuelModel();
        $contratModel = new ContratLocataireModel();
        $locataireModel = new LocataireModel();
        
        // 1. Vérifier les contrats actifs
        CLI::write("\n1. Vérification des contrats actifs...");
        $contratsActifs = $contratModel->where('statut', 'actif')->findAll();
        CLI::write("✓ " . count($contratsActifs) . " contrats actifs trouvés", 'green');
        
        foreach ($contratsActifs as $contrat) {
            $locataire = $locataireModel->find($contrat['locataire_id']);
            CLI::write("  - Contrat ID {$contrat['id']}: {$locataire['nom']} - {$contrat['loyer_mensuel']} FCFA/mois");
        }
        
        // 2. Vérifier les échéances proches
        CLI::write("\n2. Vérification des échéances proches (5 jours)...");
        $echeancesProches = $paiementModel->getEcheancesProches();
        CLI::write("✓ " . count($echeancesProches) . " échéances proches trouvées", 'yellow');
        
        foreach ($echeancesProches as $echeance) {
            $joursRestants = floor((strtotime($echeance['date_echeance']) - time()) / (60 * 60 * 24));
            CLI::write("  - {$echeance['locataire_nom']}: {$echeance['montant_du']} FCFA dans {$joursRestants} jour(s)");
        }
        
        // 3. Vérifier les retards
        CLI::write("\n3. Vérification des retards de paiement...");
        $retards = $paiementModel->getPaiementsEnRetard();
        CLI::write("✓ " . count($retards) . " paiements en retard trouvés", 'red');
        
        foreach ($retards as $retard) {
            $joursRetard = floor((time() - strtotime($retard['date_echeance'])) / (60 * 60 * 24));
            CLI::write("  - {$retard['locataire_nom']}: {$retard['montant_du']} FCFA en retard de {$joursRetard} jour(s)");
        }
        
        // 4. Test d'enregistrement d'un paiement multiple
        CLI::write("\n4. Test d'enregistrement d'un paiement multiple...");
        if (!empty($contratsActifs)) {
            $contratTest = $contratsActifs[0];
            $loyerMensuel = $contratTest['loyer_mensuel'];
            $nombreMois = 3;
            $montantTotal = $loyerMensuel * $nombreMois;
            
            CLI::write("  Test: Paiement de {$nombreMois} mois ({$montantTotal} FCFA) pour le contrat {$contratTest['id']}");
            
            try {
                $result = $paiementModel->enregistrerPaiement(
                    $contratTest['id'],
                    date('Y-m'), // Mois actuel
                    $montantTotal,
                    $nombreMois,
                    'test',
                    'TEST-' . time(),
                    'Test de paiement multiple via CLI',
                    'system'
                );
                
                if ($result) {
                    CLI::write("  ✓ Paiement multiple enregistré avec succès", 'green');
                    
                    // Vérifier que les échéances ont été créées
                    $echeancesCrees = $paiementModel->where('contrat_id', $contratTest['id'])
                                                  ->where('statut', 'paye')
                                                  ->countAllResults();
                    CLI::write("  ✓ {$echeancesCrees} échéances marquées comme payées", 'green');
                } else {
                    CLI::write("  ✗ Erreur lors de l'enregistrement du paiement multiple", 'red');
                }
            } catch (\Exception $e) {
                CLI::write("  ✗ Exception: " . $e->getMessage(), 'red');
            }
        }
        
        // 5. Vérifier les notifications qui seraient envoyées
        CLI::write("\n5. Simulation des notifications qui seraient envoyées...");
        
        // Échéances proches
        foreach ($echeancesProches as $echeance) {
            CLI::write("  📧 Notification échéance proche:");
            CLI::write("     À: {$echeance['locataire_email']}");
            CLI::write("     Sujet: Rappel: Échéance de loyer proche pour l'appartement {$echeance['appartement_adresse']}");
            CLI::write("     Montant: {$echeance['montant_du']} FCFA");
            CLI::write("     Échéance: " . date('d/m/Y', strtotime($echeance['date_echeance'])));
        }
        
        // Retards
        foreach ($retards as $retard) {
            CLI::write("  📧 Notification retard:");
            CLI::write("     À: {$retard['locataire_email']}");
            CLI::write("     Sujet: URGENT: Loyer en retard pour l'appartement {$retard['appartement_adresse']}");
            CLI::write("     Montant: {$retard['montant_du']} FCFA");
            CLI::write("     En retard depuis: " . date('d/m/Y', strtotime($retard['date_echeance'])));
        }
        
        // 6. Vérifier la cohérence des données
        CLI::write("\n6. Vérification de la cohérence des données...");
        
        $totalEcheances = $paiementModel->countAllResults();
        $echeancesPayees = $paiementModel->where('statut', 'paye')->countAllResults();
        $echeancesEnAttente = $paiementModel->where('statut', 'en_attente')->countAllResults();
        $echeancesEnRetard = $paiementModel->where('statut', 'en_retard')->countAllResults();
        
        CLI::write("  - Total échéances: {$totalEcheances}");
        CLI::write("  - Échéances payées: {$echeancesPayees}");
        CLI::write("  - Échéances en attente: {$echeancesEnAttente}");
        CLI::write("  - Échéances en retard: {$echeancesEnRetard}");
        
        $sommeVerification = $echeancesPayees + $echeancesEnAttente + $echeancesEnRetard;
        if ($sommeVerification === $totalEcheances) {
            CLI::write("  ✓ Cohérence des données vérifiée", 'green');
        } else {
            CLI::write("  ✗ Incohérence détectée dans les données", 'red');
        }
        
        // 7. Recommandations
        CLI::write("\n7. Recommandations pour la configuration SMTP:");
        CLI::write("  - Configurer les paramètres SMTP dans app/Config/Email.php");
        CLI::write("  - Tester l'envoi d'emails avec: php spark test:email");
        CLI::write("  - Planifier la commande check:echeances dans un cron job");
        CLI::write("  - Configurer les notifications des gestionnaires dans leurs profils");
        
        CLI::write("\n=== FIN DU TEST ===", 'green');
    }
}