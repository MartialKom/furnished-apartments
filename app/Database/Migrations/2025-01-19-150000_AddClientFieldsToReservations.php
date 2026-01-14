<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddClientFieldsToReservations extends Migration
{
    public function up()
    {
        // Vérifier quelles colonnes existent déjà
        $existingColumns = $this->db->getFieldNames('reservations');
        
        $fields = [];
        
        // Ajouter seulement les colonnes qui n'existent pas
        if (!in_array('client_nom', $existingColumns)) {
            $fields['client_nom'] = [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'locataire_id'
            ];
        }
        
        if (!in_array('client_email', $existingColumns)) {
            $fields['client_email'] = [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'client_nom'
            ];
        }
        
        if (!in_array('client_telephone', $existingColumns)) {
            $fields['client_telephone'] = [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'client_email'
            ];
        }
        
        // Les colonnes de réduction existent déjà, on les ignore
        
        if (!empty($fields)) {
            $this->forge->addColumn('reservations', $fields);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('reservations', ['client_nom', 'client_email', 'client_telephone', 'reduction_pourcentage', 'montant_reduction', 'prix_original']);
    }
}
