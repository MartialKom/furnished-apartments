<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDiscountFieldsToReservations extends Migration
{
    public function up()
    {
        // Vérifier quelles colonnes existent déjà
        $existingColumns = $this->db->getFieldNames('reservations');
        
        $fields = [];
        
        // Ajouter seulement les colonnes qui n'existent pas
        if (!in_array('reduction_pourcentage', $existingColumns)) {
            $fields['reduction_pourcentage'] = [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => '0.00',
                'null' => false,
                'after' => 'notes'
            ];
        }
        
        if (!in_array('montant_reduction', $existingColumns)) {
            $fields['montant_reduction'] = [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => '0.00',
                'null' => false,
                'after' => 'reduction_pourcentage'
            ];
        }
        
        if (!in_array('prix_original', $existingColumns)) {
            $fields['prix_original'] = [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'after' => 'montant_reduction'
            ];
        }
        
        if (!empty($fields)) {
            $this->forge->addColumn('reservations', $fields);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('reservations', ['reduction_pourcentage', 'montant_reduction', 'prix_original']);
    }
}
