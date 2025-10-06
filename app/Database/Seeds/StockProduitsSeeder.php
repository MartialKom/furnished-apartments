<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StockProduitsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Hygiène et Toilette (categorie_id = 1)
            [
                'categorie_id'   => 1,
                'nom'            => 'Savon liquide 500ml',
                'description'    => 'Savon liquide pour les mains',
                'unite_mesure'   => 'bouteille',
                'stock_minimum'  => 10,
                'stock_actuel'   => 0,
                'prix_moyen'     => 0,
                'actif'          => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'categorie_id'   => 1,
                'nom'            => 'Gel douche 250ml',
                'description'    => 'Gel douche parfumé',
                'unite_mesure'   => 'bouteille',
                'stock_minimum'  => 15,
                'stock_actuel'   => 0,
                'prix_moyen'     => 0,
                'actif'          => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'categorie_id'   => 1,
                'nom'            => 'Papier toilette (rouleau)',
                'description'    => 'Papier toilette double épaisseur',
                'unite_mesure'   => 'rouleau',
                'stock_minimum'  => 50,
                'stock_actuel'   => 0,
                'prix_moyen'     => 0,
                'actif'          => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'categorie_id'   => 1,
                'nom'            => 'Shampoing 200ml',
                'description'    => 'Shampoing tous types de cheveux',
                'unite_mesure'   => 'bouteille',
                'stock_minimum'  => 10,
                'stock_actuel'   => 0,
                'prix_moyen'     => 0,
                'actif'          => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],

            // Nettoyage (categorie_id = 2)
            [
                'categorie_id'   => 2,
                'nom'            => 'Détergent multi-usage 1L',
                'description'    => 'Produit de nettoyage polyvalent',
                'unite_mesure'   => 'litre',
                'stock_minimum'  => 5,
                'stock_actuel'   => 0,
                'prix_moyen'     => 0,
                'actif'          => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'categorie_id'   => 2,
                'nom'            => 'Eau de Javel 2L',
                'description'    => 'Désinfectant et blanchissant',
                'unite_mesure'   => 'litre',
                'stock_minimum'  => 8,
                'stock_actuel'   => 0,
                'prix_moyen'     => 0,
                'actif'          => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'categorie_id'   => 2,
                'nom'            => 'Serpillière microfibre',
                'description'    => 'Serpillière réutilisable',
                'unite_mesure'   => 'pièce',
                'stock_minimum'  => 5,
                'stock_actuel'   => 0,
                'prix_moyen'     => 0,
                'actif'          => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],

            // Linge et Literie (categorie_id = 3)
            [
                'categorie_id'   => 3,
                'nom'            => 'Drap housse 2 places',
                'description'    => 'Drap housse blanc 160x200cm',
                'unite_mesure'   => 'pièce',
                'stock_minimum'  => 10,
                'stock_actuel'   => 0,
                'prix_moyen'     => 0,
                'actif'          => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'categorie_id'   => 3,
                'nom'            => 'Serviette de bain',
                'description'    => 'Serviette éponge 70x140cm',
                'unite_mesure'   => 'pièce',
                'stock_minimum'  => 20,
                'stock_actuel'   => 0,
                'prix_moyen'     => 0,
                'actif'          => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'categorie_id'   => 3,
                'nom'            => 'Taie d\'oreiller',
                'description'    => 'Taie d\'oreiller blanche 65x65cm',
                'unite_mesure'   => 'pièce',
                'stock_minimum'  => 15,
                'stock_actuel'   => 0,
                'prix_moyen'     => 0,
                'actif'          => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],

            // Cuisine et Vaisselle (categorie_id = 4)
            [
                'categorie_id'   => 4,
                'nom'            => 'Gobelet jetable (paquet 50)',
                'description'    => 'Gobelets en plastique transparent',
                'unite_mesure'   => 'paquet',
                'stock_minimum'  => 5,
                'stock_actuel'   => 0,
                'prix_moyen'     => 0,
                'actif'          => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'categorie_id'   => 4,
                'nom'            => 'Liquide vaisselle 750ml',
                'description'    => 'Produit vaisselle concentré',
                'unite_mesure'   => 'bouteille',
                'stock_minimum'  => 8,
                'stock_actuel'   => 0,
                'prix_moyen'     => 0,
                'actif'          => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],

            // Équipements (categorie_id = 5)
            [
                'categorie_id'   => 5,
                'nom'            => 'Ampoule LED E27 12W',
                'description'    => 'Ampoule LED économique blanc chaud',
                'unite_mesure'   => 'pièce',
                'stock_minimum'  => 10,
                'stock_actuel'   => 0,
                'prix_moyen'     => 0,
                'actif'          => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'categorie_id'   => 5,
                'nom'            => 'Pile AA (paquet 4)',
                'description'    => 'Piles alcalines AA',
                'unite_mesure'   => 'paquet',
                'stock_minimum'  => 5,
                'stock_actuel'   => 0,
                'prix_moyen'     => 0,
                'actif'          => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('stock_produits')->insertBatch($data);

        echo "✅ 14 produits de stock créés avec succès!\n";
    }
}
