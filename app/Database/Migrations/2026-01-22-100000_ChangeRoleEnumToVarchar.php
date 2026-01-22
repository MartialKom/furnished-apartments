<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeRoleEnumToVarchar extends Migration
{
    public function up()
    {
        // Changer le champ 'role' de ENUM à VARCHAR pour supporter les rôles dynamiques
        $this->db->query("ALTER TABLE utilisateurs MODIFY COLUMN role VARCHAR(50) NULL");

        echo "Le champ 'role' a été changé de ENUM à VARCHAR(50).\n";
    }

    public function down()
    {
        // Revenir à ENUM (attention: les valeurs non-standard seront perdues)
        $this->db->query("ALTER TABLE utilisateurs MODIFY COLUMN role ENUM('admin', 'gestionnaire') NOT NULL DEFAULT 'gestionnaire'");
    }
}
