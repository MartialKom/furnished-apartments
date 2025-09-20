<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        echo "=== CRÉATION COMPLÈTE DES DONNÉES DE TEST ===\n\n";
        
        // 1. Créer les locataires à long terme et leurs contrats
        echo "1. Création des locataires et contrats à long terme...\n";
        $this->call('LongTermTenantsSeeder');
        
        echo "\n";
        
        // 2. Ajouter des données de test pour les paiements
        echo "2. Ajout des données de test pour les paiements...\n";
        $this->call('PaymentTestDataSeeder');
        
        echo "\n";
        
        // 3. Ajouter des données de test pour les notifications
        echo "3. Ajout des données de test pour les notifications...\n";
        $this->call('NotificationTestDataSeeder');
        
        echo "\n=== RÉSUMÉ DES DONNÉES CRÉÉES ===\n";
        
        // Afficher les statistiques
        $this->afficherStatistiques();
        
        echo "\n=== DONNÉES DE TEST COMPLÈTES ===\n";
        echo "Vous pouvez maintenant tester le système des locataires à long terme !\n";
        echo "\nURLs de test:\n";
        echo "- Dashboard paiements: /admin/paiements-mensuels/dashboard\n";
        echo "- Liste contrats: /admin/contrats\n";
        echo "- Créer contrat: /admin/contrats/create\n";
        echo "\nCommande de test:\n";
        echo "php spark check:echeances\n";
    }
    
    private function afficherStatistiques()
    {
        // Statistiques des locataires
        $totalLocataires = $this->db->table('locataires')->countAllResults();
        echo "✓ Total locataires: $totalLocataires\n";
        
        // Statistiques des contrats
        $totalContrats = $this->db->table('contrats_locataires')->countAllResults();
        $contratsActifs = $this->db->table('contrats_locataires')->where('statut', 'actif')->countAllResults();
        echo "✓ Total contrats: $totalContrats (actifs: $contratsActifs)\n";
        
        // Statistiques des paiements
        $totalEcheances = $this->db->table('paiements_mensuels')->countAllResults();
        $echeancesPayees = $this->db->table('paiements_mensuels')->where('statut', 'paye')->countAllResults();
        $echeancesRetard = $this->db->table('paiements_mensuels')->where('statut', 'en_retard')->countAllResults();
        $echeancesAttente = $this->db->table('paiements_mensuels')->where('statut', 'en_attente')->countAllResults();
        
        echo "✓ Total échéances: $totalEcheances\n";
        echo "  - Payées: $echeancesPayees\n";
        echo "  - En attente: $echeancesAttente\n";
        echo "  - En retard: $echeancesRetard\n";
        
        // Statistiques financières
        $totalDu = $this->db->table('paiements_mensuels')->selectSum('montant_du')->get()->getRow()->montant_du ?? 0;
        $totalPaye = $this->db->table('paiements_mensuels')->selectSum('montant_paye')->get()->getRow()->montant_paye ?? 0;
        
        echo "✓ Montants:\n";
        echo "  - Total dû: " . number_format($totalDu, 0, ',', ' ') . " FCFA\n";
        echo "  - Total payé: " . number_format($totalPaye, 0, ',', ' ') . " FCFA\n";
        echo "  - Restant: " . number_format($totalDu - $totalPaye, 0, ',', ' ') . " FCFA\n";
    }
}
