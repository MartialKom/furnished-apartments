<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MigrateExistingUsersRoleId extends Migration
{
    public function up()
    {
        // Récupérer tous les utilisateurs sans role_id
        $usersWithoutRoleId = $this->db->table('utilisateurs')
            ->where('role_id IS NULL OR role_id = 0')
            ->get()
            ->getResultArray();

        if (empty($usersWithoutRoleId)) {
            echo "Aucun utilisateur à migrer.\n";
            return;
        }

        // Récupérer tous les rôles
        $roles = $this->db->table('roles')->get()->getResultArray();
        $rolesByCode = [];
        foreach ($roles as $role) {
            $rolesByCode[strtolower($role['code'])] = $role['id'];
        }

        $migrated = 0;

        foreach ($usersWithoutRoleId as $user) {
            $oldRole = strtolower(trim($user['role'] ?? ''));
            $roleId = null;

            // Correspondance directe
            if (isset($rolesByCode[$oldRole])) {
                $roleId = $rolesByCode[$oldRole];
            }
            // Correspondances alternatives
            elseif ($oldRole === 'admin') {
                // Chercher admin, administrateur ou super_admin
                $roleId = $rolesByCode['administrateur'] ?? $rolesByCode['super_admin'] ?? $rolesByCode['admin'] ?? null;
            }
            // Si aucune correspondance, utiliser gestionnaire par défaut
            elseif (isset($rolesByCode['gestionnaire'])) {
                $roleId = $rolesByCode['gestionnaire'];
            }

            if ($roleId) {
                $this->db->table('utilisateurs')
                    ->where('id', $user['id'])
                    ->update(['role_id' => $roleId]);
                $migrated++;
                echo "Utilisateur {$user['id']} ({$user['nomUtilisateur']}): role_id = {$roleId}\n";
            } else {
                echo "ERREUR: Utilisateur {$user['id']} ({$user['nomUtilisateur']}): aucun rôle trouvé pour '{$oldRole}'\n";
            }
        }

        echo "\n{$migrated} utilisateur(s) migré(s) sur " . count($usersWithoutRoleId) . " total.\n";
    }

    public function down()
    {
        // Ne rien faire - on ne veut pas supprimer les role_id
        echo "Cette migration ne peut pas être annulée.\n";
    }
}
