<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddModePaiementToReservations extends Migration
{
    public function up()
    {
        // Ajouter le champ mode_paiement à la table reservations
        $fields = [
            'mode_paiement' => [
                'type' => 'ENUM',
                'constraint' => ['especes', 'orange_money', 'momo', 'carte_bancaire', 'virement'],
                'default' => 'especes',
                'null' => false,
                'comment' => 'Mode de paiement utilisé',
                'after' => 'montant_restant'
            ]
        ];

        $this->forge->addColumn('reservations', $fields);

        // Créer un index pour faciliter les recherches par mode de paiement
        $this->db->query("CREATE INDEX idx_mode_paiement ON reservations(mode_paiement)");
    }

    public function down()
    {
        // Supprimer l'index
        $this->db->query("DROP INDEX idx_mode_paiement ON reservations");

        // Supprimer la colonne
        $this->forge->dropColumn('reservations', 'mode_paiement');
    }
}
