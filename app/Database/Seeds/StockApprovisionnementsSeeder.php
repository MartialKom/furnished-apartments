<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StockApprovisionnementsSeeder extends Seeder
{
    public function run()
    {
        // Approvisionnements de test avec différents fournisseurs et dates
        $data = [
            // Savon liquide (produit_id = 1)
            [
                'produit_id'              => 1,
                'quantite'                => 30,
                'prix_unitaire'           => 500,
                'prix_total'              => 15000,
                'fournisseur'             => 'Supermarché SOCOCE',
                'reference_facture'       => 'SOCO-2025-001',
                'date_approvisionnement'  => '2025-01-15',
                'notes'                   => 'Approvisionnement initial',
                'utilisateur_id'          => 1,
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            ],
            // Gel douche (produit_id = 2)
            [
                'produit_id'              => 2,
                'quantite'                => 40,
                'prix_unitaire'           => 600,
                'prix_total'              => 24000,
                'fournisseur'             => 'Supermarché SOCOCE',
                'reference_facture'       => 'SOCO-2025-001',
                'date_approvisionnement'  => '2025-01-15',
                'notes'                   => null,
                'utilisateur_id'          => 1,
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            ],
            // Papier toilette (produit_id = 3)
            [
                'produit_id'              => 3,
                'quantite'                => 120,
                'prix_unitaire'           => 150,
                'prix_total'              => 18000,
                'fournisseur'             => 'Grossiste Chez Mamadou',
                'reference_facture'       => 'MAMA-2025-045',
                'date_approvisionnement'  => '2025-02-01',
                'notes'                   => 'Achat en gros - prix avantageux',
                'utilisateur_id'          => 1,
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            ],
            // Shampoing (produit_id = 4)
            [
                'produit_id'              => 4,
                'quantite'                => 25,
                'prix_unitaire'           => 800,
                'prix_total'              => 20000,
                'fournisseur'             => 'Supermarché SOCOCE',
                'reference_facture'       => 'SOCO-2025-023',
                'date_approvisionnement'  => '2025-02-10',
                'notes'                   => null,
                'utilisateur_id'          => 1,
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            ],
            // Détergent (produit_id = 5)
            [
                'produit_id'              => 5,
                'quantite'                => 20,
                'prix_unitaire'           => 1500,
                'prix_total'              => 30000,
                'fournisseur'             => 'Grossiste Produits Ménagers',
                'reference_facture'       => 'GPM-2025-078',
                'date_approvisionnement'  => '2025-02-15',
                'notes'                   => null,
                'utilisateur_id'          => 1,
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            ],
            // Eau de Javel (produit_id = 6)
            [
                'produit_id'              => 6,
                'quantite'                => 25,
                'prix_unitaire'           => 800,
                'prix_total'              => 20000,
                'fournisseur'             => 'Grossiste Produits Ménagers',
                'reference_facture'       => 'GPM-2025-078',
                'date_approvisionnement'  => '2025-02-15',
                'notes'                   => null,
                'utilisateur_id'          => 1,
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            ],
            // Serpillière (produit_id = 7)
            [
                'produit_id'              => 7,
                'quantite'                => 15,
                'prix_unitaire'           => 1200,
                'prix_total'              => 18000,
                'fournisseur'             => 'Marché Adjamé',
                'reference_facture'       => null,
                'date_approvisionnement'  => '2025-03-01',
                'notes'                   => 'Achat au marché',
                'utilisateur_id'          => 1,
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            ],
            // Drap housse (produit_id = 8)
            [
                'produit_id'              => 8,
                'quantite'                => 25,
                'prix_unitaire'           => 3500,
                'prix_total'              => 87500,
                'fournisseur'             => 'Textiles Ivoire',
                'reference_facture'       => 'TI-2025-112',
                'date_approvisionnement'  => '2025-03-05',
                'notes'                   => 'Qualité supérieure',
                'utilisateur_id'          => 1,
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            ],
            // Serviette de bain (produit_id = 9)
            [
                'produit_id'              => 9,
                'quantite'                => 50,
                'prix_unitaire'           => 2500,
                'prix_total'              => 125000,
                'fournisseur'             => 'Textiles Ivoire',
                'reference_facture'       => 'TI-2025-112',
                'date_approvisionnement'  => '2025-03-05',
                'notes'                   => null,
                'utilisateur_id'          => 1,
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            ],
            // Taie d'oreiller (produit_id = 10)
            [
                'produit_id'              => 10,
                'quantite'                => 40,
                'prix_unitaire'           => 1000,
                'prix_total'              => 40000,
                'fournisseur'             => 'Textiles Ivoire',
                'reference_facture'       => 'TI-2025-112',
                'date_approvisionnement'  => '2025-03-05',
                'notes'                   => null,
                'utilisateur_id'          => 1,
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            ],
            // Gobelet jetable (produit_id = 11)
            [
                'produit_id'              => 11,
                'quantite'                => 20,
                'prix_unitaire'           => 2000,
                'prix_total'              => 40000,
                'fournisseur'             => 'Grossiste Chez Mamadou',
                'reference_facture'       => 'MAMA-2025-089',
                'date_approvisionnement'  => '2025-03-20',
                'notes'                   => 'Paquet de 50 gobelets',
                'utilisateur_id'          => 1,
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            ],
            // Liquide vaisselle (produit_id = 12)
            [
                'produit_id'              => 12,
                'quantite'                => 30,
                'prix_unitaire'           => 1200,
                'prix_total'              => 36000,
                'fournisseur'             => 'Supermarché SOCOCE',
                'reference_facture'       => 'SOCO-2025-067',
                'date_approvisionnement'  => '2025-03-25',
                'notes'                   => null,
                'utilisateur_id'          => 1,
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            ],
            // Ampoule LED (produit_id = 13)
            [
                'produit_id'              => 13,
                'quantite'                => 30,
                'prix_unitaire'           => 1800,
                'prix_total'              => 54000,
                'fournisseur'             => 'Quincaillerie Moderne',
                'reference_facture'       => 'QM-2025-234',
                'date_approvisionnement'  => '2025-04-01',
                'notes'                   => 'Ampoules longue durée',
                'utilisateur_id'          => 1,
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            ],
            // Pile AA (produit_id = 14)
            [
                'produit_id'              => 14,
                'quantite'                => 25,
                'prix_unitaire'           => 1500,
                'prix_total'              => 37500,
                'fournisseur'             => 'Quincaillerie Moderne',
                'reference_facture'       => 'QM-2025-234',
                'date_approvisionnement'  => '2025-04-01',
                'notes'                   => 'Paquet de 4 piles',
                'utilisateur_id'          => 1,
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('stock_approvisionnements')->insertBatch($data);

        // Mettre à jour les stocks et prix moyens des produits
        foreach ($data as $appro) {
            $produit = $this->db->table('stock_produits')->where('id', $appro['produit_id'])->get()->getRow();

            $ancienStock = $produit->stock_actuel;
            $ancienPrixMoyen = $produit->prix_moyen;

            $nouveauStock = $ancienStock + $appro['quantite'];

            if ($nouveauStock > 0) {
                $nouveauPrixMoyen = (($ancienStock * $ancienPrixMoyen) + ($appro['quantite'] * $appro['prix_unitaire'])) / $nouveauStock;
            } else {
                $nouveauPrixMoyen = $appro['prix_unitaire'];
            }

            $this->db->table('stock_produits')
                ->where('id', $appro['produit_id'])
                ->update([
                    'stock_actuel' => $nouveauStock,
                    'prix_moyen'   => $nouveauPrixMoyen,
                    'updated_at'   => date('Y-m-d H:i:s'),
                ]);
        }

        echo "✅ 14 approvisionnements créés et stocks mis à jour avec succès!\n";
    }
}
