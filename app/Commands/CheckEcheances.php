<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\PaiementMensuelModel;
use App\Models\ContratLocataireModel;
use App\Libraries\NotificationService;

class CheckEcheances extends BaseCommand
{
    protected $group       = 'Maintenance';
    protected $name        = 'check:echeances';
    protected $description = 'Vérifie les échéances de paiement et envoie les notifications';

    public function run(array $params)
    {
        CLI::write('=== VÉRIFICATION DES ÉCHÉANCES ===', 'yellow');
        
        $paiementModel = new PaiementMensuelModel();
        $contratModel = new ContratLocataireModel();
        $notificationService = new NotificationService();
        
        // 1. Mettre à jour les statuts des paiements en retard
        CLI::write('Mise à jour des statuts de retard...', 'cyan');
        $retardsMisAJour = $paiementModel->mettreAJourStatutsRetard();
        CLI::write("✓ Statuts mis à jour", 'green');
        
        // 2. Obtenir les échéances proches (dans les 5 prochains jours)
        CLI::write('Vérification des échéances proches...', 'cyan');
        $echeancesProches = $paiementModel->getEcheancesProches();
        
        $notificationsEnvoyees = 0;
        foreach ($echeancesProches as $echeance) {
            $contrat = $contratModel->getContratAvecDetails($echeance['contrat_id']);
            $locataire = [
                'nom' => $echeance['locataire_nom'],
                'email' => $echeance['locataire_email'],
                'telephone' => $echeance['locataire_telephone']
            ];
            
            if ($notificationService->envoyerNotificationEcheance($locataire, $echeance, $contrat)) {
                $notificationsEnvoyees++;
                CLI::write("✓ Notification envoyée à " . $locataire['nom'], 'green');
            } else {
                CLI::write("✗ Échec envoi notification à " . $locataire['nom'], 'red');
            }
        }
        
        // 3. Obtenir les paiements en retard
        CLI::write('Vérification des retards de paiement...', 'cyan');
        $retards = $paiementModel->getPaiementsEnRetard();
        
        $notificationsRetard = 0;
        foreach ($retards as $retard) {
            $contrat = $contratModel->getContratAvecDetails($retard['contrat_id']);
            $locataire = [
                'nom' => $retard['locataire_nom'],
                'email' => $retard['locataire_email'],
                'telephone' => $retard['locataire_telephone']
            ];
            
            if ($notificationService->envoyerNotificationRetard($locataire, $retard, $contrat)) {
                $notificationsRetard++;
                CLI::write("✓ Notification retard envoyée à " . $locataire['nom'], 'green');
            } else {
                CLI::write("✗ Échec envoi notification retard à " . $locataire['nom'], 'red');
            }
        }
        
        // 4. Générer des échéances pour les nouveaux contrats
        CLI::write('Génération des échéances pour nouveaux contrats...', 'cyan');
        $contratsActifs = $contratModel->getContratsActifs();
        $echeancesGenerees = 0;
        
        foreach ($contratsActifs as $contrat) {
            // Vérifier s'il y a des échéances pour ce contrat
            $echeancesExistantes = $paiementModel->where('contrat_id', $contrat['id'])->countAllResults();
            
            if ($echeancesExistantes == 0) {
                if ($contratModel->genererEcheancesMensuelles($contrat['id'], 12)) {
                    $echeancesGenerees++;
                    CLI::write("✓ Échéances générées pour contrat ID " . $contrat['id'], 'green');
                }
            }
        }
        
        // Résumé
        CLI::write("\n=== RÉSUMÉ ===", 'yellow');
        CLI::write("Échéances proches trouvées : " . count($echeancesProches), 'white');
        CLI::write("Notifications échéances envoyées : " . $notificationsEnvoyees, 'green');
        CLI::write("Retards de paiement : " . count($retards), 'white');
        CLI::write("Notifications retard envoyées : " . $notificationsRetard, 'green');
        CLI::write("Échéances générées : " . $echeancesGenerees, 'green');
        
        CLI::write("\n=== FIN DE LA VÉRIFICATION ===", 'yellow');
    }
}