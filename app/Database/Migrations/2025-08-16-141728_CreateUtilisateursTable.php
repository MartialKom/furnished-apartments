<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUtilisateursTable extends Migration
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
            'nom' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'prenom' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'nomUtilisateur' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'unique' => true,
            ],
            'telephone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
                'unique' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'role' => [
                'type' => 'ENUM',
                'constraint' => ['admin', 'gestionnaire'],
                'null' => false,
            ],
            'motDePasse' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
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
        $this->forge->createTable('utilisateurs');
    }

    public function down()
    {
        $this->forge->dropTable('utilisateurs');
    }
}
