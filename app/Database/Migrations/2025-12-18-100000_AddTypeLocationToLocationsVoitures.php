<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTypeLocationToLocationsVoitures extends Migration
{
    public function up()
    {
        $fields = [
            'type_location' => [
                'type' => 'ENUM',
                'constraint' => ['jour', 'heure'],
                'default' => 'jour',
                'null' => false,
                'after' => 'voiture_id'
            ],
            'duree_heures' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Durée en heures pour les locations horaires',
                'after' => 'nombre_jours'
            ],
            'tarif_horaire' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'comment' => 'Tarif horaire de la voiture',
                'after' => 'tarif_journalier'
            ]
        ];

        $this->forge->addColumn('locations_voitures', $fields);

        // Modifier la colonne nombre_jours pour accepter NULL (pour les locations horaires)
        $this->db->query('ALTER TABLE locations_voitures MODIFY nombre_jours INT(11) NULL');
    }

    public function down()
    {
        $this->forge->dropColumn('locations_voitures', ['type_location', 'duree_heures', 'tarif_horaire']);

        // Remettre nombre_jours en NOT NULL
        $this->db->query('ALTER TABLE locations_voitures MODIFY nombre_jours INT(11) NOT NULL');
    }
}
