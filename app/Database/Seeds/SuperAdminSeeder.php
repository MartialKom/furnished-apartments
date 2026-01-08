<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        echo "Création du compte Super Admin...\n\n";

        // Get super admin role
        $superAdminRole = $this->db->table('roles')
            ->where('code', 'super_admin')
            ->get()
            ->getRowArray();

        if (!$superAdminRole) {
            echo "✗ Erreur: Rôle Super Admin non trouvé. Exécutez d'abord RolesSeeder.\n";
            return;
        }

        // Check if super admin user already exists
        $existing = $this->db->table('utilisateurs')
            ->where('nomUtilisateur', 'superadmin')
            ->get()
            ->getRowArray();

        if ($existing) {
            echo "- Super Admin existe déjà.\n";
            echo "  Username: superadmin\n";
            echo "  ID: {$existing['id']}\n";
            return;
        }

        // Create super admin user
        $utilisateurModel = new \App\Models\UtilisateurModel();

        $superAdminData = [
            'nom' => 'Super',
            'prenom' => 'Admin',
            'nomUtilisateur' => 'superadmin',
            'telephone' => '+237600000000',
            'email' => 'superadmin@t-lodge.com',
            'role' => 'admin', // Keep for backward compatibility
            'role_id' => $superAdminRole['id'],
            'motDePasse' => 'SuperAdmin@2025!', // Strong password
            'statut' => 'actif'
        ];

        $userId = $utilisateurModel->insert($superAdminData);

        if ($userId) {
            echo "✓ Super Admin créé avec succès!\n\n";
            echo "═══════════════════════════════════════════════\n";
            echo "  INFORMATIONS DE CONNEXION SUPER ADMIN\n";
            echo "═══════════════════════════════════════════════\n";
            echo "  Username: superadmin\n";
            echo "  Password: SuperAdmin@2025!\n";
            echo "  Email:    superadmin@t-lodge.com\n";
            echo "═══════════════════════════════════════════════\n";
            echo "  ⚠️  IMPORTANT: Changez ce mot de passe\n";
            echo "     après la première connexion!\n";
            echo "═══════════════════════════════════════════════\n\n";
        } else {
            echo "✗ Erreur lors de la création du Super Admin.\n";
        }
    }
}
