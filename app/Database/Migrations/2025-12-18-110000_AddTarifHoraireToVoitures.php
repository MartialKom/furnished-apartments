<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTarifHoraireToVoitures extends Migration
{
    public function up()
    {
        $fields = [
            'tarif_horaire' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'comment' => 'Tarif horaire de la voiture',
                'after' => 'tarif_journalier'
            ]
        ];

        $this->forge->addColumn('voitures', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('voitures', 'tarif_horaire');
    }
}
