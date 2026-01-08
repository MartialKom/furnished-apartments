<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRoleIdToUtilisateurs extends Migration
{
    public function up()
    {
        $fields = [
            'role_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'ID du rôle assigné (nouveau système)',
                'after'      => 'role',
            ],
        ];

        $this->forge->addColumn('utilisateurs', $fields);

        // Add foreign key constraint
        $this->db->query('
            ALTER TABLE utilisateurs
            ADD CONSTRAINT fk_utilisateurs_role
            FOREIGN KEY (role_id)
            REFERENCES roles(id)
            ON DELETE SET NULL
            ON UPDATE CASCADE
        ');

        // Add index
        $this->forge->processIndexes('utilisateurs');
    }

    public function down()
    {
        // Drop foreign key first
        $this->db->query('ALTER TABLE utilisateurs DROP FOREIGN KEY fk_utilisateurs_role');

        // Drop column
        $this->forge->dropColumn('utilisateurs', 'role_id');
    }
}
