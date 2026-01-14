<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPresentielToTypeReservation extends Migration
{
    public function up()
    {
        // Modifier le champ type_reservation pour ajouter 'presentiel'
        $sql = "ALTER TABLE reservations
                MODIFY COLUMN type_reservation ENUM('en_ligne', 'telephonique', 'presentiel')
                NOT NULL DEFAULT 'en_ligne'";

        $this->db->query($sql);
    }

    public function down()
    {
        // Revenir à l'ancien ENUM
        $sql = "ALTER TABLE reservations
                MODIFY COLUMN type_reservation ENUM('en_ligne', 'telephonique')
                NOT NULL DEFAULT 'en_ligne'";

        $this->db->query($sql);
    }
}
