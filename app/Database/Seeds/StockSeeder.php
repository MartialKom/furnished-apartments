<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run()
    {
        echo "\n🚀 Début du peuplement du module Stock...\n\n";

        // 1. Catégories
        echo "📁 Création des catégories...\n";
        $this->call('StockCategoriesSeeder');

        // 2. Produits
        echo "\n📦 Création des produits...\n";
        $this->call('StockProduitsSeeder');

        // 3. Approvisionnements (qui mettent à jour les stocks)
        echo "\n🚚 Création des approvisionnements...\n";
        $this->call('StockApprovisionnementsSeeder');

        // 4. Sorties (qui déduisent du stock)
        echo "\n📤 Création des sorties de stock...\n";
        $this->call('StockSortiesSeeder');

        echo "\n✨ Peuplement du module Stock terminé avec succès!\n";
        echo "\n📊 Résumé:\n";
        echo "   - 5 catégories créées\n";
        echo "   - 14 produits créés\n";
        echo "   - 14 approvisionnements enregistrés\n";
        echo "   - 14 sorties enregistrées\n";
        echo "\n🎯 Vous pouvez maintenant consulter le module Stock dans l'admin!\n\n";
    }
}
