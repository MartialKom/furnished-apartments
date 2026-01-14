<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MigrateUsersToNewRoles extends Migration
{
    public function up()
    {
        // Get role IDs from the roles table
        $adminRole = $this->db->table('roles')
            ->where('code', 'administrateur')
            ->get()
            ->getRowArray();

        $gestionnaireRole = $this->db->table('roles')
            ->where('code', 'gestionnaire')
            ->get()
            ->getRowArray();

        if (!$adminRole || !$gestionnaireRole) {
            throw new \RuntimeException(
                'Les rôles par défaut (administrateur, gestionnaire) doivent être créés avant cette migration. ' .
                'Exécutez le seeder RolePermissionSystemSeeder avant cette migration.'
            );
        }

        // Migrate users with 'admin' role
        $adminCount = $this->db->table('utilisateurs')
            ->where('role', 'admin')
            ->update(['role_id' => $adminRole['id']]);

        // Migrate users with 'gestionnaire' role
        $gestionnaireCount = $this->db->table('utilisateurs')
            ->where('role', 'gestionnaire')
            ->update(['role_id' => $gestionnaireRole['id']]);

        log_message('info', "Migration des utilisateurs: {$adminCount} admin(s), {$gestionnaireCount} gestionnaire(s)");
    }

    public function down()
    {
        // Reset role_id to null for all users
        $this->db->table('utilisateurs')->update(['role_id' => null]);

        log_message('info', 'Rollback: Tous les role_id réinitialisés à NULL');
    }
}
