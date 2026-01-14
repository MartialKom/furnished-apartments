<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolePermissionSystemSeeder extends Seeder
{
    public function run()
    {
        echo "\n";
        echo "═══════════════════════════════════════════════════════════\n";
        echo "  Installation du Système de Rôles et Permissions T-Lodge\n";
        echo "═══════════════════════════════════════════════════════════\n";
        echo "\n";

        // 1. Create permissions
        echo "ÉTAPE 1/3: Création des permissions...\n";
        echo "───────────────────────────────────────────────────────────\n";
        $this->call('PermissionsSeeder');
        echo "\n";

        // 2. Create roles and assign default permissions
        echo "ÉTAPE 2/3: Création des rôles et attribution des permissions...\n";
        echo "───────────────────────────────────────────────────────────\n";
        $this->call('RolesSeeder');
        echo "\n";

        // 3. Create super admin user
        echo "ÉTAPE 3/3: Création du compte Super Admin...\n";
        echo "───────────────────────────────────────────────────────────\n";
        $this->call('SuperAdminSeeder');

        echo "═══════════════════════════════════════════════════════════\n";
        echo "  ✓ Installation terminée avec succès!\n";
        echo "═══════════════════════════════════════════════════════════\n";
        echo "\n";
        echo "PROCHAINES ÉTAPES:\n";
        echo "1. Exécutez la migration: php spark migrate\n";
        echo "   (si pas encore fait)\n";
        echo "2. Exécutez la migration de données utilisateurs:\n";
        echo "   php spark migrate (MigrateUsersToNewRoles)\n";
        echo "3. Testez la connexion avec le Super Admin\n";
        echo "4. Configurez les rôles personnalisés si nécessaire\n";
        echo "\n";
    }
}
