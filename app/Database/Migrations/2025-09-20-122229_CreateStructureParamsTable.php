<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStructureParamsTable extends Migration
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
            'param_key' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
                'comment' => 'Clé du paramètre'
            ],
            'param_value' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Valeur du paramètre'
            ],
            'param_type' => [
                'type' => 'ENUM',
                'constraint' => ['string', 'text', 'number', 'boolean', 'email', 'phone', 'url'],
                'default' => 'string',
                'null' => false,
                'comment' => 'Type du paramètre'
            ],
            'param_group' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'default' => 'general',
                'comment' => 'Groupe du paramètre'
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Description du paramètre'
            ],
            'is_required' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'null' => false,
                'comment' => 'Paramètre obligatoire'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('param_key', false, true); // Clé unique
        $this->forge->createTable('structure_params');
    }

    public function down()
    {
        $this->forge->dropTable('structure_params');
    }
}