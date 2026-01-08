<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddModePaiementToPaiements extends Migration
{
    public function up()
    {
        $this->forge->addColumn('paiements', [
            'mode_paiement' => [
                'type' => 'ENUM',
                'constraint' => ['especes', 'orange_money', 'momo', 'carte_bancaire', 'virement'],
                'default' => 'especes',
                'null' => true,
                'after' => 'date',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('paiements', 'mode_paiement');
    }
}
