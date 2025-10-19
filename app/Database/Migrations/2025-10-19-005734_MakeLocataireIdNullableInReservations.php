<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeLocataireIdNullableInReservations extends Migration
{
    public function up()
    {
        // D'abord, supprimer la contrainte de clé étrangère
        $this->forge->dropForeignKey('reservations', 'reservations_locataire_id_foreign');

        // Modifier la colonne pour accepter NULL
        $fields = [
            'locataire_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true, // Maintenant nullable
            ],
        ];

        $this->forge->modifyColumn('reservations', $fields);

        // Recréer la contrainte de clé étrangère avec SET NULL au lieu de CASCADE
        $this->db->query('ALTER TABLE reservations ADD CONSTRAINT reservations_locataire_id_foreign FOREIGN KEY (locataire_id) REFERENCES locataires(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        // Supprimer la nouvelle contrainte
        $this->forge->dropForeignKey('reservations', 'reservations_locataire_id_foreign');

        // Remettre NOT NULL (attention : cela échouera si des NULL existent)
        $fields = [
            'locataire_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
        ];

        $this->forge->modifyColumn('reservations', $fields);

        // Recréer l'ancienne contrainte
        $this->db->query('ALTER TABLE reservations ADD CONSTRAINT reservations_locataire_id_foreign FOREIGN KEY (locataire_id) REFERENCES locataires(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }
}
