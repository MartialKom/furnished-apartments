<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UtilisateurSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nom' => 'Admin',
                'prenom' => 'Système',
                'nomUtilisateur' => 'admin',
                'telephone' => '+237600000001',
                'email' => 'admin@furnished-apartments.com',
                'role' => 'admin',
                'motDePasse' => 'admin123'
            ],
            [
                'nom' => 'Gestionnaire',
                'prenom' => 'Principal',
                'nomUtilisateur' => 'gestionnaire',
                'telephone' => '+237600000002',
                'email' => 'gestionnaire@furnished-apartments.com',
                'role' => 'gestionnaire',
                'motDePasse' => 'gestionnaire123'
            ],
            [
                'nom' => 'Doe',
                'prenom' => 'John',
                'nomUtilisateur' => 'johndoe',
                'telephone' => '+237600000003',
                'email' => 'john.doe@furnished-apartments.com',
                'role' => 'gestionnaire',
                'motDePasse' => 'password123'
            ]
        ];

        // Utilisation du modèle pour profiter du hashage automatique
        $utilisateurModel = new \App\Models\UtilisateurModel();
        
        foreach ($data as $userData) {
            // Vérifier si l'utilisateur existe déjà par nomUtilisateur ou téléphone
            $existingUser = $utilisateurModel->groupStart()
                                          ->where('nomUtilisateur', $userData['nomUtilisateur'])
                                          ->orWhere('telephone', $userData['telephone'])
                                          ->groupEnd()
                                          ->first();
            
            if (!$existingUser) {
                $utilisateurModel->insert($userData);
                echo "Utilisateur '{$userData['prenom']} {$userData['nom']}' (@{$userData['nomUtilisateur']}) créé avec succès.\n";
            } else {
                echo "Utilisateur avec le nom d'utilisateur '{$userData['nomUtilisateur']}' ou téléphone '{$userData['telephone']}' existe déjà.\n";
            }
        }
    }
}
