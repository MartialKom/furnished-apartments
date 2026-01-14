<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePermissionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nom' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
                'comment'    => 'Nom descriptif de la permission',
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
                'comment'    => 'Code unique pour vérification (ex: view_dashboard)',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'module' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
                'comment'    => 'Module concerné (dashboard, appartements, etc.)',
            ],
            'groupe' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'comment'    => 'Groupe pour affichage UI (Navigation, Gestion Appartements, etc.)',
            ],
            'ordre' => [
                'type'    => 'INT',
                'constraint' => 11,
                'default' => 0,
                'null'    => false,
                'comment' => 'Ordre d\'affichage dans l\'interface',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code');
        $this->forge->addKey('module');
        $this->forge->addKey('groupe');
        $this->forge->createTable('permissions');
    }

    public function down()
    {
        $this->forge->dropTable('permissions');
    }
}
