<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyVoituresPhotosField extends Migration
{
    public function up()
    {
        // Modifier le champ 'photo' en 'photos' et changer le type en TEXT
        $this->forge->modifyColumn('voitures', [
            'photo' => [
                'name' => 'photos',
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Chemins des photos du véhicule séparés par des virgules (max 3)'
            ]
        ]);
    }

    public function down()
    {
        // Revenir à l'ancien champ 'photo' VARCHAR
        $this->forge->modifyColumn('voitures', [
            'photos' => [
                'name' => 'photo',
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Chemin de la photo du véhicule'
            ]
        ]);
    }
}
