<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run()
    {
        // Define default roles
        $roles = [
            [
                'nom' => 'Super Administrateur',
                'code' => 'super_admin',
                'description' => 'Accès complet et illimité au système. Ce rôle ne peut pas être modifié ou supprimé.',
                'is_system' => 1,
                'is_super_admin' => 1,
                'statut' => 'actif'
            ],
            [
                'nom' => 'Administrateur',
                'code' => 'administrateur',
                'description' => 'Accès complet à toutes les fonctionnalités du système',
                'is_system' => 1,
                'is_super_admin' => 0,
                'statut' => 'actif'
            ],
            [
                'nom' => 'Gestionnaire',
                'code' => 'gestionnaire',
                'description' => 'Accès aux fonctionnalités de gestion quotidienne (appartements, réservations, stock)',
                'is_system' => 1,
                'is_super_admin' => 0,
                'statut' => 'actif'
            ]
        ];

        foreach ($roles as $role) {
            // Check if role already exists
            $existing = $this->db->table('roles')
                ->where('code', $role['code'])
                ->get()
                ->getRowArray();

            if (!$existing) {
                $this->db->table('roles')->insert($role);
                echo "✓ Rôle '{$role['nom']}' créé.\n";
            } else {
                echo "- Rôle '{$role['nom']}' existe déjà.\n";
            }
        }

        echo "\n";

        // Assign default permissions to roles
        $this->assignDefaultPermissions();
    }

    /**
     * Assign default permissions to roles
     */
    private function assignDefaultPermissions()
    {
        echo "Attribution des permissions aux rôles...\n\n";

        // Get roles
        $adminRole = $this->db->table('roles')->where('code', 'administrateur')->get()->getRowArray();
        $gestionnaireRole = $this->db->table('roles')->where('code', 'gestionnaire')->get()->getRowArray();

        if (!$adminRole || !$gestionnaireRole) {
            echo "✗ Erreur: Rôles non trouvés.\n";
            return;
        }

        // Get all permissions
        $allPermissions = $this->db->table('permissions')->select('id, code')->get()->getResultArray();

        if (empty($allPermissions)) {
            echo "✗ Erreur: Aucune permission trouvée. Exécutez d'abord PermissionsSeeder.\n";
            return;
        }

        // Admin gets ALL permissions
        $adminCount = 0;
        foreach ($allPermissions as $permission) {
            $existing = $this->db->table('role_permissions')
                ->where('role_id', $adminRole['id'])
                ->where('permission_id', $permission['id'])
                ->get()
                ->getRowArray();

            if (!$existing) {
                $this->db->table('role_permissions')->insert([
                    'role_id' => $adminRole['id'],
                    'permission_id' => $permission['id']
                ]);
                $adminCount++;
            }
        }
        echo "✓ Administrateur: {$adminCount} permissions assignées.\n";

        // Gestionnaire gets permissions EXCEPT: utilisateurs, roles, parametres, analytics
        $excludedPermissions = [
            'view_utilisateurs', 'manage_utilisateurs',
            'view_roles', 'manage_roles',
            'view_parametres', 'manage_parametres',
            'view_analytics'
        ];

        $gestionnaireCount = 0;
        foreach ($allPermissions as $permission) {
            if (!in_array($permission['code'], $excludedPermissions)) {
                $existing = $this->db->table('role_permissions')
                    ->where('role_id', $gestionnaireRole['id'])
                    ->where('permission_id', $permission['id'])
                    ->get()
                    ->getRowArray();

                if (!$existing) {
                    $this->db->table('role_permissions')->insert([
                        'role_id' => $gestionnaireRole['id'],
                        'permission_id' => $permission['id']
                    ]);
                    $gestionnaireCount++;
                }
            }
        }
        echo "✓ Gestionnaire: {$gestionnaireCount} permissions assignées.\n";
        echo "\n✓ Permissions assignées avec succès!\n";
    }
}
