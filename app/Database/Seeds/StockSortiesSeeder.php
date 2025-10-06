<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StockSortiesSeeder extends Seeder
{
    public function run()
    {
        // Sorties de stock vers les appartements
        $data = [
            // Distribution pour Appartement A101 - Nouvel arrivant
            [
                'produit_id'     => 1, // Savon liquide
                'quantite'       => 2,
                'appartement_id' => 1,
                'destination'    => null,
                'motif'          => 'Nouvel arrivant - Kit d\'accueil',
                'date_sortie'    => '2025-02-20',
                'notes'          => null,
                'utilisateur_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'produit_id'     => 2, // Gel douche
                'quantite'       => 2,
                'appartement_id' => 1,
                'destination'    => null,
                'motif'          => 'Nouvel arrivant - Kit d\'accueil',
                'date_sortie'    => '2025-02-20',
                'notes'          => null,
                'utilisateur_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'produit_id'     => 3, // Papier toilette
                'quantite'       => 6,
                'appartement_id' => 1,
                'destination'    => null,
                'motif'          => 'Nouvel arrivant - Kit d\'accueil',
                'date_sortie'    => '2025-02-20',
                'notes'          => null,
                'utilisateur_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],

            // Distribution pour Appartement B202
            [
                'produit_id'     => 1, // Savon liquide
                'quantite'       => 1,
                'appartement_id' => 2,
                'destination'    => null,
                'motif'          => 'Réapprovisionnement mensuel',
                'date_sortie'    => '2025-03-01',
                'notes'          => null,
                'utilisateur_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'produit_id'     => 3, // Papier toilette
                'quantite'       => 4,
                'appartement_id' => 2,
                'destination'    => null,
                'motif'          => 'Réapprovisionnement mensuel',
                'date_sortie'    => '2025-03-01',
                'notes'          => null,
                'utilisateur_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],

            // Nettoyage des parties communes
            [
                'produit_id'     => 5, // Détergent
                'quantite'       => 3,
                'appartement_id' => null,
                'destination'    => 'Parties communes - Hall et couloirs',
                'motif'          => 'Nettoyage hebdomadaire',
                'date_sortie'    => '2025-03-10',
                'notes'          => 'Nettoyage complet de l\'immeuble',
                'utilisateur_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'produit_id'     => 6, // Eau de Javel
                'quantite'       => 2,
                'appartement_id' => null,
                'destination'    => 'Parties communes - Sanitaires',
                'motif'          => 'Désinfection sanitaires communs',
                'date_sortie'    => '2025-03-10',
                'notes'          => null,
                'utilisateur_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],

            // Changement de linge - Appartement C301
            [
                'produit_id'     => 8, // Drap housse
                'quantite'       => 2,
                'appartement_id' => 3,
                'destination'    => null,
                'motif'          => 'Changement de linge usagé',
                'date_sortie'    => '2025-03-15',
                'notes'          => null,
                'utilisateur_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'produit_id'     => 9, // Serviette de bain
                'quantite'       => 4,
                'appartement_id' => 3,
                'destination'    => null,
                'motif'          => 'Changement de linge usagé',
                'date_sortie'    => '2025-03-15',
                'notes'          => null,
                'utilisateur_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'produit_id'     => 10, // Taie d'oreiller
                'quantite'       => 4,
                'appartement_id' => 3,
                'destination'    => null,
                'motif'          => 'Changement de linge usagé',
                'date_sortie'    => '2025-03-15',
                'notes'          => null,
                'utilisateur_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],

            // Maintenance - Remplacement ampoules
            [
                'produit_id'     => 13, // Ampoule LED
                'quantite'       => 3,
                'appartement_id' => 5,
                'destination'    => null,
                'motif'          => 'Remplacement ampoules défectueuses',
                'date_sortie'    => '2025-04-05',
                'notes'          => '2 salon + 1 chambre',
                'utilisateur_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'produit_id'     => 13, // Ampoule LED
                'quantite'       => 2,
                'appartement_id' => null,
                'destination'    => 'Parties communes - Couloirs',
                'motif'          => 'Remplacement ampoules grillées',
                'date_sortie'    => '2025-04-05',
                'notes'          => 'Couloir 2ème étage',
                'utilisateur_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],

            // Distribution papier toilette multiple appartements
            [
                'produit_id'     => 3, // Papier toilette
                'quantite'       => 8,
                'appartement_id' => 6,
                'destination'    => null,
                'motif'          => 'Réapprovisionnement mensuel',
                'date_sortie'    => '2025-04-10',
                'notes'          => null,
                'utilisateur_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'produit_id'     => 3, // Papier toilette
                'quantite'       => 6,
                'appartement_id' => 7,
                'destination'    => null,
                'motif'          => 'Réapprovisionnement mensuel',
                'date_sortie'    => '2025-04-10',
                'notes'          => null,
                'utilisateur_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('stock_sorties')->insertBatch($data);

        // Mettre à jour les stocks des produits
        foreach ($data as $sortie) {
            $produit = $this->db->table('stock_produits')->where('id', $sortie['produit_id'])->get()->getRow();

            $nouveauStock = max(0, $produit->stock_actuel - $sortie['quantite']);

            $this->db->table('stock_produits')
                ->where('id', $sortie['produit_id'])
                ->update([
                    'stock_actuel' => $nouveauStock,
                    'updated_at'   => date('Y-m-d H:i:s'),
                ]);
        }

        echo "✅ 14 sorties de stock créées et stocks mis à jour avec succès!\n";
    }
}
