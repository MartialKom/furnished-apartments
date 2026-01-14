<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NotificationTestDataSeeder extends Seeder
{
    public function run()
    {
        echo "=== CRÉATION DES DONNÉES DE TEST POUR NOTIFICATIONS ===\n";
        
        // 1. Créer des échéances proches (dans les 5 prochains jours) pour tester les notifications
        $this->creerEcheancesProches();
        
        // 2. Créer des échéances pour aujourd'hui
        $this->creerEcheancesAujourdhui();
        
        // 3. Créer des échéances avec différents statuts pour tester le dashboard
        $this->creerEcheancesVarietes();
        
        echo "\n=== DONNÉES DE TEST POUR NOTIFICATIONS CRÉÉES ===\n";
    }
    
    private function creerEcheancesProches()
    {
        $contrats = $this->db->table('contrats_locataires')->where('statut', 'actif')->get()->getResultArray();
        $aujourdhui = new \DateTime();
        
        // Créer des échéances dans les 5 prochains jours
        for ($i = 1; $i <= 5; $i++) {
            $dateEcheance = clone $aujourdhui;
            $dateEcheance->add(new \DateInterval("P{$i}D"));
            
            $jourDuMois = $dateEcheance->format('j');
            $moisAnnee = $dateEcheance->format('Y-m');
            
            // Trouver un contrat avec un jour de paiement proche
            foreach ($contrats as $contrat) {
                if (abs($contrat['jour_paiement'] - $jourDuMois) <= 2) {
                    // Vérifier si l'échéance existe déjà
                    $existing = $this->db->table('paiements_mensuels')
                        ->where('contrat_id', $contrat['id'])
                        ->where('mois_annee', $moisAnnee)
                        ->countAllResults();
                        
                    if ($existing == 0) {
                        $dateEcheanceContrat = $dateEcheance->format('Y-m-d');
                        
                        $echeance = [
                            'contrat_id' => $contrat['id'],
                            'mois_annee' => $moisAnnee,
                            'montant_du' => $contrat['loyer_mensuel'],
                            'montant_paye' => 0,
                            'date_echeance' => $dateEcheanceContrat,
                            'date_paiement' => null,
                            'statut' => 'en_attente',
                            'nombre_mois_payes' => 1,
                            'mode_paiement' => null,
                            'reference_paiement' => null,
                            'notes' => "Échéance de test - notification dans $i jour(s)",
                            'enregistre_par' => null,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];
                        
                        $this->db->table('paiements_mensuels')->insert($echeance);
                        echo "✓ Échéance proche créée: $dateEcheanceContrat pour contrat ID {$contrat['id']}\n";
                        break; // Une échéance par jour
                    }
                }
            }
        }
    }
    
    private function creerEcheancesAujourdhui()
    {
        $aujourdhui = new \DateTime();
        $jourDuMois = $aujourdhui->format('j');
        $moisAnnee = $aujourdhui->format('Y-m');
        
        $contrats = $this->db->table('contrats_locataires')
            ->where('statut', 'actif')
            ->where('jour_paiement', $jourDuMois)
            ->get()
            ->getResultArray();
            
        foreach ($contrats as $contrat) {
            // Vérifier si l'échéance existe déjà
            $existing = $this->db->table('paiements_mensuels')
                ->where('contrat_id', $contrat['id'])
                ->where('mois_annee', $moisAnnee)
                ->countAllResults();
                
            if ($existing == 0) {
                $echeance = [
                    'contrat_id' => $contrat['id'],
                    'mois_annee' => $moisAnnee,
                    'montant_du' => $contrat['loyer_mensuel'],
                    'montant_paye' => 0,
                    'date_echeance' => $aujourdhui->format('Y-m-d'),
                    'date_paiement' => null,
                    'statut' => 'en_attente',
                    'nombre_mois_payes' => 1,
                    'mode_paiement' => null,
                    'reference_paiement' => null,
                    'notes' => 'Échéance aujourd\'hui - test notification immédiate',
                    'enregistre_par' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                $this->db->table('paiements_mensuels')->insert($echeance);
                echo "✓ Échéance aujourd'hui créée pour contrat ID {$contrat['id']}\n";
            }
        }
    }
    
    private function creerEcheancesVarietes()
    {
        // Créer des échéances avec différents statuts pour tester l'affichage
        $varietes = [
            [
                'contrat_id' => 1,
                'mois_annee' => '2024-12',
                'montant_du' => 450000,
                'montant_paye' => 225000,
                'date_echeance' => '2024-12-05',
                'statut' => 'partiellement_paye',
                'notes' => 'Paiement partiel - solde: 225,000 FCFA'
            ],
            [
                'contrat_id' => 2,
                'mois_annee' => '2024-11',
                'montant_du' => 380000,
                'montant_paye' => 380000,
                'date_echeance' => '2024-11-10',
                'date_paiement' => '2024-11-08',
                'statut' => 'paye',
                'mode_paiement' => 'cheque',
                'reference_paiement' => 'CHQ-001234',
                'notes' => 'Paiement par chèque - encaissé'
            ],
            [
                'contrat_id' => 3,
                'mois_annee' => '2024-10',
                'montant_du' => 520000,
                'montant_paye' => 0,
                'date_echeance' => '2024-10-01',
                'statut' => 'en_retard',
                'notes' => 'Retard de 20 jours - procédure de recouvrement en cours'
            ]
        ];
        
        foreach ($varietes as $echeance) {
            // Vérifier si l'échéance existe déjà
            $existing = $this->db->table('paiements_mensuels')
                ->where('contrat_id', $echeance['contrat_id'])
                ->where('mois_annee', $echeance['mois_annee'])
                ->countAllResults();
                
            if ($existing == 0) {
                $echeance['created_at'] = date('Y-m-d H:i:s');
                $echeance['updated_at'] = date('Y-m-d H:i:s');
                $echeance['nombre_mois_payes'] = 1;
                
                if (!isset($echeance['date_paiement']) && $echeance['statut'] === 'paye') {
                    $echeance['date_paiement'] = $echeance['date_echeance'];
                }
                
                $this->db->table('paiements_mensuels')->insert($echeance);
                echo "✓ Échéance variété créée: {$echeance['statut']} pour contrat ID {$echeance['contrat_id']}\n";
            }
        }
    }
}
