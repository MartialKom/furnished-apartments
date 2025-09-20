<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestUsersSeeder extends Seeder
{
    public function run()
    {
        echo "=== CRÉATION DES UTILISATEURS DE TEST ===\n";
        
        $users = [
            [
                'nom' => 'Admin',
                'prenom' => 'Principal',
                'nomUtilisateur' => 'admin',
                'telephone' => '+2250700000001',
                'email' => 'admin@test.com',
                'role' => 'admin',
                'motDePasse' => password_hash('admin123', PASSWORD_DEFAULT),
                'statut' => 'actif',
                'notifications_email' => true,
                'heure_notification' => '09:00:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nom' => 'Gestionnaire',
                'prenom' => 'Test',
                'nomUtilisateur' => 'gestionnaire',
                'telephone' => '+2250700000002',
                'email' => 'gestionnaire@test.com',
                'role' => 'gestionnaire',
                'motDePasse' => password_hash('gestion123', PASSWORD_DEFAULT),
                'statut' => 'actif',
                'notifications_email' => true,
                'heure_notification' => '08:30:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nom' => 'Superviseur',
                'prenom' => 'Paiements',
                'nomUtilisateur' => 'superviseur',
                'telephone' => '+2250700000003',
                'email' => 'superviseur@test.com',
                'role' => 'gestionnaire',
                'motDePasse' => password_hash('super123', PASSWORD_DEFAULT),
                'statut' => 'actif',
                'notifications_email' => true,
                'heure_notification' => '10:00:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        foreach ($users as $user) {
            // Vérifier si l'utilisateur existe déjà
            $existing = $this->db->table('utilisateurs')
                ->where('nomUtilisateur', $user['nomUtilisateur'])
                ->countAllResults();
                
            if ($existing == 0) {
                $this->db->table('utilisateurs')->insert($user);
                echo "✓ Utilisateur créé: {$user['nomUtilisateur']} ({$user['role']})\n";
            } else {
                echo "⚠ Utilisateur existe déjà: {$user['nomUtilisateur']}\n";
            }
        }
        
        echo "\n=== CRÉDENTIALS DE TEST ===\n";
        echo "Admin: admin / admin123\n";
        echo "Gestionnaire: gestionnaire / gestion123\n";
        echo "Superviseur: superviseur / super123\n";
        echo "\n=== UTILISATEURS DE TEST CRÉÉS ===\n";
    }
}
