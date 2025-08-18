<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UtilisateurSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nom' => 'admin',
                'role' => 'admin',
                'motDePasse' => 'admin123', // Sera hashé automatiquement par le modèle
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nom' => 'gestionnaire',
                'role' => 'gestionnaire',
                'motDePasse' => 'gestionnaire123', // Sera hashé automatiquement par le modèle
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Utilisation du modèle pour profiter du hashage automatique
        $utilisateurModel = new \App\Models\UtilisateurModel();
        
        foreach ($data as $userData) {
            // Vérifier si l'utilisateur existe déjà
            if (!$utilisateurModel->where('nom', $userData['nom'])->first()) {
                $utilisateurModel->insert($userData);
                echo "Utilisateur '{$userData['nom']}' créé avec succès.\n";
            } else {
                echo "Utilisateur '{$userData['nom']}' existe déjà.\n";
            }
        }
    }
}
