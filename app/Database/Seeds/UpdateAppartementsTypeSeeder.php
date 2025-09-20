<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateAppartementsTypeSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Mettre à jour tous les appartements existants pour qu'ils aient le type 'meuble' par défaut
        $db->query("UPDATE appartements SET type = 'meuble' WHERE type IS NULL OR type = ''");
        
        echo "Tous les appartements existants ont été mis à jour avec le type 'meuble' par défaut.\n";
    }
}
