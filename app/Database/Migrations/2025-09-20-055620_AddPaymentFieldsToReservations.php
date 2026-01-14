<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentFieldsToReservations extends Migration
{
    public function up()
    {
        $fields = [
            'montant_paye' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => '0.00',
                'null' => false,
                'after' => 'montant_total'
            ],
            'montant_restant' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'after' => 'montant_paye'
            ],
            'type_reservation' => [
                'type' => 'ENUM',
                'constraint' => ['en_ligne', 'telephonique'],
                'default' => 'en_ligne',
                'null' => false,
                'after' => 'montant_restant'
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'type_reservation'
            ]
        ];
        
        $this->forge->addColumn('reservations', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('reservations', ['montant_paye', 'montant_restant', 'type_reservation', 'notes']);
    }
}
