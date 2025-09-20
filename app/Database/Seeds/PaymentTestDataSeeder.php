<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PaymentTestDataSeeder extends Seeder
{
    public function run()
    {
        echo "=== CRÉATION DES DONNÉES DE TEST POUR PAIEMENTS ===\n";
        
        // 1. Ajouter des échéances futures pour tester les notifications
        $contrats = $this->db->table('contrats_locataires')->where('statut', 'actif')->get()->getResultArray();
        
        foreach ($contrats as $contrat) {
            $this->ajouterEcheancesFutures($contrat);
        }
        
        // 2. Ajouter quelques paiements en retard pour tester les alertes
        $this->ajouterPaiementsRetard();
        
        // 3. Ajouter des paiements multiples (plusieurs mois en une fois)
        $this->ajouterPaiementsMultiples();
        
        echo "\n=== DONNÉES DE TEST POUR PAIEMENTS CRÉÉES ===\n";
    }
    
    private function ajouterEcheancesFutures($contrat)
    {
        $dateActuelle = new \DateTime();
        $jourPaiement = $contrat['jour_paiement'];
        $loyerMensuel = $contrat['loyer_mensuel'];
        
        // Ajouter 3 échéances futures
        for ($i = 1; $i <= 3; $i++) {
            $dateFuture = clone $dateActuelle;
            $dateFuture->add(new \DateInterval("P{$i}M"));
            
            $moisAnnee = $dateFuture->format('Y-m');
            $dateEcheance = $dateFuture->format('Y-' . str_pad($jourPaiement, 2, '0', STR_PAD_LEFT));
            
            // Vérifier si l'échéance existe déjà
            $existing = $this->db->table('paiements_mensuels')
                ->where('contrat_id', $contrat['id'])
                ->where('mois_annee', $moisAnnee)
                ->countAllResults();
                
            if ($existing == 0) {
                $echeance = [
                    'contrat_id' => $contrat['id'],
                    'mois_annee' => $moisAnnee,
                    'montant_du' => $loyerMensuel,
                    'montant_paye' => 0,
                    'date_echeance' => $dateEcheance,
                    'date_paiement' => null,
                    'statut' => 'en_attente',
                    'nombre_mois_payes' => 1,
                    'mode_paiement' => null,
                    'reference_paiement' => null,
                    'notes' => null,
                    'enregistre_par' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                $this->db->table('paiements_mensuels')->insert($echeance);
                echo "✓ Échéance future ajoutée: $moisAnnee pour contrat ID {$contrat['id']}\n";
            }
        }
    }
    
    private function ajouterPaiementsRetard()
    {
        // Créer quelques paiements en retard pour tester les alertes
        $retards = [
            [
                'contrat_id' => 1,
                'mois_annee' => '2024-08',
                'montant_du' => 450000,
                'montant_paye' => 0,
                'date_echeance' => '2024-08-05',
                'statut' => 'en_retard',
                'notes' => 'Retard de paiement - rappel envoyé'
            ],
            [
                'contrat_id' => 2,
                'mois_annee' => '2024-07',
                'montant_du' => 380000,
                'montant_paye' => 0,
                'date_echeance' => '2024-07-10',
                'statut' => 'en_retard',
                'notes' => 'Retard important - contact téléphonique requis'
            ]
        ];
        
        foreach ($retards as $retard) {
            // Vérifier si l'échéance existe déjà
            $existing = $this->db->table('paiements_mensuels')
                ->where('contrat_id', $retard['contrat_id'])
                ->where('mois_annee', $retard['mois_annee'])
                ->countAllResults();
                
            if ($existing == 0) {
                $retard['created_at'] = date('Y-m-d H:i:s');
                $retard['updated_at'] = date('Y-m-d H:i:s');
                $retard['nombre_mois_payes'] = 1;
                
                $this->db->table('paiements_mensuels')->insert($retard);
                echo "✓ Paiement en retard ajouté: {$retard['mois_annee']} pour contrat ID {$retard['contrat_id']}\n";
            }
        }
    }
    
    private function ajouterPaiementsMultiples()
    {
        // Ajouter des paiements pour plusieurs mois en une fois
        $paiementsMultiples = [
            [
                'contrat_id' => 3,
                'mois_annee' => '2024-09',
                'montant_du' => 520000,
                'montant_paye' => 1560000, // 3 mois payés
                'date_echeance' => '2024-09-01',
                'date_paiement' => date('Y-m-d'),
                'statut' => 'paye',
                'nombre_mois_payes' => 3,
                'mode_paiement' => 'virement',
                'reference_paiement' => 'VIR-MULTI-001',
                'notes' => 'Paiement de 3 mois en une fois - septembre à novembre 2024',
                'enregistre_par' => 1
            ],
            [
                'contrat_id' => 4,
                'mois_annee' => '2024-10',
                'montant_du' => 320000,
                'montant_paye' => 640000, // 2 mois payés
                'date_echeance' => '2024-10-15',
                'date_paiement' => date('Y-m-d'),
                'statut' => 'paye',
                'nombre_mois_payes' => 2,
                'mode_paiement' => 'mobile_money',
                'reference_paiement' => 'MTN-20240920-001',
                'notes' => 'Paiement mobile money - octobre et novembre 2024',
                'enregistre_par' => 2
            ]
        ];
        
        foreach ($paiementsMultiples as $paiement) {
            // Vérifier si l'échéance existe déjà
            $existing = $this->db->table('paiements_mensuels')
                ->where('contrat_id', $paiement['contrat_id'])
                ->where('mois_annee', $paiement['mois_annee'])
                ->countAllResults();
                
            if ($existing == 0) {
                $paiement['created_at'] = date('Y-m-d H:i:s');
                $paiement['updated_at'] = date('Y-m-d H:i:s');
                
                $this->db->table('paiements_mensuels')->insert($paiement);
                echo "✓ Paiement multiple ajouté: {$paiement['nombre_mois_payes']} mois pour contrat ID {$paiement['contrat_id']}\n";
            }
        }
    }
}