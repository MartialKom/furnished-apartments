<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEnCoursTermineeToReservationStatut extends Migration
{
    public function up()
    {
        // Modifier le champ statut pour ajouter 'en_cours' et 'terminee'
        $this->db->query("
            ALTER TABLE reservations
            MODIFY COLUMN statut ENUM('en_attente','confirmee','en_cours','terminee','annulee')
            NOT NULL DEFAULT 'en_attente'
        ");
    }

    public function down()
    {
        // Retour à l'ancien état (sans 'en_cours' et 'terminee')
        $this->db->query("
            ALTER TABLE reservations
            MODIFY COLUMN statut ENUM('en_attente','confirmee','annulee')
            NOT NULL DEFAULT 'en_attente'
        ");
    }
}
