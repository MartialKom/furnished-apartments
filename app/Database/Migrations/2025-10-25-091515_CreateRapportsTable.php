<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRapportsTable extends Migration
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
            'utilisateur_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'date_rapport' => [
                'type' => 'DATE',
            ],
            'contenu' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('utilisateur_id', 'utilisateurs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('rapports');
    }

    public function down()
    {
        $this->forge->dropTable('rapports');
    }
}
