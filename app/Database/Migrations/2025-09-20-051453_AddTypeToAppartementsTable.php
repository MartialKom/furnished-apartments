<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTypeToAppartementsTable extends Migration
{
    public function up()
    {
        $fields = [
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['meuble', 'non_meuble'],
                'default' => 'meuble',
                'null' => false,
                'after' => 'statut'
            ]
        ];
        
        $this->forge->addColumn('appartements', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('appartements', 'type');
    }
}
