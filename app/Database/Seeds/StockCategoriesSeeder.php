<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StockCategoriesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nom'         => 'Hygiène et Toilette',
                'description' => 'Produits d\'hygiène corporelle et sanitaires',
                'actif'       => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nom'         => 'Nettoyage',
                'description' => 'Produits de nettoyage et d\'entretien',
                'actif'       => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nom'         => 'Linge et Literie',
                'description' => 'Draps, serviettes et articles de lit',
                'actif'       => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nom'         => 'Cuisine et Vaisselle',
                'description' => 'Articles pour la cuisine et produits jetables',
                'actif'       => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nom'         => 'Équipements',
                'description' => 'Petits équipements et accessoires',
                'actif'       => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('stock_categories')->insertBatch($data);

        echo "✅ 5 catégories de stock créées avec succès!\n";
    }
}
