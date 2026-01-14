<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LongTermTenantsSeeder extends Seeder
{
    public function run()
    {
        echo "=== CRÉATION DES DONNÉES DE TEST POUR LOCATAIRES LONG TERME ===\n";
        
        // 1. Créer des locataires à long terme
        $locataires = [
            [
                'nom' => 'Jean-Baptiste KOUAME',
                'email' => 'jb.kouame@email.com',
                'telephone' => '+2250701234567',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nom' => 'Marie-Claire TRAORE',
                'email' => 'mc.traore@email.com',
                'telephone' => '+2250702345678',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nom' => 'Kouassi KOFFI',
                'email' => 'k.koffi@email.com',
                'telephone' => '+2250703456789',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nom' => 'Fatou DIABATE',
                'email' => 'f.diabate@email.com',
                'telephone' => '+2250704567890',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nom' => 'Ahmed SANGARE',
                'email' => 'a.sangare@email.com',
                'telephone' => '+2250705678901',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        $locataireIds = [];
        foreach ($locataires as $locataire) {
            $locataireId = $this->db->table('locataires')->insert($locataire);
            if ($locataireId) {
                $locataireIds[] = $this->db->insertID();
                echo "✓ Locataire créé: {$locataire['nom']} (ID: " . $this->db->insertID() . ")\n";
            }
        }
        
        // 2. Créer des contrats de long terme
        $contrats = [
            [
                'locataire_id' => $locataireIds[0],
                'appartement_id' => 1, // Supposons que l'appartement 1 existe
                'date_debut' => '2024-01-01',
                'date_fin' => null, // Contrat indéterminé
                'loyer_mensuel' => 450000,
                'jour_paiement' => 5,
                'caution' => 900000,
                'statut' => 'actif',
                'conditions_particulieres' => 'Contrat indéterminé. Loyer négocié pour longue durée. Clauses de résiliation: 3 mois de préavis.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'locataire_id' => $locataireIds[1],
                'appartement_id' => 2,
                'date_debut' => '2024-02-15',
                'date_fin' => null,
                'loyer_mensuel' => 380000,
                'jour_paiement' => 10,
                'caution' => 760000,
                'statut' => 'actif',
                'conditions_particulieres' => 'Contrat étudiant avec réduction. Présentation de la carte d\'étudiant requise chaque année.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'locataire_id' => $locataireIds[2],
                'appartement_id' => 3,
                'date_debut' => '2023-11-01',
                'date_fin' => null,
                'loyer_mensuel' => 520000,
                'jour_paiement' => 1,
                'caution' => 1040000,
                'statut' => 'actif',
                'conditions_particulieres' => 'Contrat cadre supérieur. Loyer majoré pour appartement premium. Option d\'achat possible après 3 ans.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'locataire_id' => $locataireIds[3],
                'appartement_id' => 4,
                'date_debut' => '2024-03-01',
                'date_fin' => null,
                'loyer_mensuel' => 320000,
                'jour_paiement' => 15,
                'caution' => 640000,
                'statut' => 'actif',
                'conditions_particulieres' => 'Contrat famille monoparentale. Réduction sociale accordée. Renouvellement automatique.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'locataire_id' => $locataireIds[4],
                'appartement_id' => 5,
                'date_debut' => '2024-01-15',
                'date_fin' => '2024-12-31', // Contrat déterminé
                'loyer_mensuel' => 410000,
                'jour_paiement' => 20,
                'caution' => 820000,
                'statut' => 'actif',
                'conditions_particulieres' => 'Contrat mission expatriée. Durée déterminée avec possibilité de renouvellement.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        $contratIds = [];
        foreach ($contrats as $contrat) {
            $contratId = $this->db->table('contrats_locataires')->insert($contrat);
            if ($contratId) {
                $contratIds[] = $this->db->insertID();
                echo "✓ Contrat créé pour locataire ID {$contrat['locataire_id']} (Contrat ID: " . $this->db->insertID() . ")\n";
            }
        }
        
        // 3. Mettre à jour le statut des appartements (occuper)
        $appartementIds = [1, 2, 3, 4, 5];
        foreach ($appartementIds as $appartementId) {
            $this->db->table('appartements')->where('id', $appartementId)->update(['statut' => 'occupe']);
            echo "✓ Appartement ID $appartementId marqué comme occupé\n";
        }
        
        // 4. Générer les échéances mensuelles pour chaque contrat
        foreach ($contratIds as $index => $contratId) {
            $contrat = $contrats[$index];
            $this->genererEcheancesPourContrat($contratId, $contrat);
        }
        
        echo "\n=== DONNÉES DE TEST CRÉÉES AVEC SUCCÈS ===\n";
        echo "Locataires créés: " . count($locataireIds) . "\n";
        echo "Contrats créés: " . count($contratIds) . "\n";
        echo "Appartements mis à jour: " . count($appartementIds) . "\n";
    }
    
    private function genererEcheancesPourContrat($contratId, $contrat)
    {
        $dateDebut = new \DateTime($contrat['date_debut']);
        $jourPaiement = $contrat['jour_paiement'];
        $loyerMensuel = $contrat['loyer_mensuel'];
        
        // Générer les échéances pour 12 mois à partir de la date de début
        for ($i = 0; $i < 12; $i++) {
            $moisAnnee = $dateDebut->format('Y-m');
            $dateEcheance = $dateDebut->format('Y-' . str_pad($jourPaiement, 2, '0', STR_PAD_LEFT));
            
            // Déterminer le statut selon la date
            $statut = 'en_attente';
            $datePaiement = null;
            $montantPaye = 0;
            
            // Simuler quelques paiements pour tester
            if ($i < 3) {
                // Les 3 premiers mois sont payés
                $statut = 'paye';
                $datePaiement = $dateEcheance;
                $montantPaye = $loyerMensuel;
            } elseif ($i == 3) {
                // Le 4ème mois est partiellement payé
                $statut = 'partiellement_paye';
                $montantPaye = $loyerMensuel * 0.5;
            } elseif ($i == 4) {
                // Le 5ème mois est en retard
                $statut = 'en_retard';
            }
            
            $echeance = [
                'contrat_id' => $contratId,
                'mois_annee' => $moisAnnee,
                'montant_du' => $loyerMensuel,
                'montant_paye' => $montantPaye,
                'date_echeance' => $dateEcheance,
                'date_paiement' => $datePaiement,
                'statut' => $statut,
                'nombre_mois_payes' => 1,
                'mode_paiement' => $statut === 'paye' ? 'virement' : null,
                'reference_paiement' => $statut === 'paye' ? 'VIR-' . strtoupper(substr(md5($moisAnnee), 0, 8)) : null,
                'notes' => $statut === 'partiellement_paye' ? 'Paiement partiel - solde à régulariser' : null,
                'enregistre_par' => 1, // Admin ID
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->table('paiements_mensuels')->insert($echeance);
            
            $dateDebut->add(new \DateInterval('P1M'));
        }
        
        echo "✓ 12 échéances générées pour contrat ID $contratId\n";
    }
}