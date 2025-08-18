<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAppartementsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'adresse' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'photos' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'equipements' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tarifs' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'statut' => [
                'type' => 'ENUM',
                'constraint' => ['disponible', 'occupe', 'maintenance'],
                'default' => 'disponible',
                'null' => false,
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
        $this->forge->createTable('appartements');
    }

    public function down()
    {
        $this->forge->dropTable('appartements');
    }
}
