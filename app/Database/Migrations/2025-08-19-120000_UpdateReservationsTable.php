<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateReservationsTable extends Migration
{
    public function up()
    {
        // Renommer les colonnes pour correspondre au modèle
        $this->forge->modifyColumn('reservations', [
            'dateDebut' => [
                'name' => 'date_debut',
                'type' => 'DATE',
                'null' => false,
            ],
            'dateFin' => [
                'name' => 'date_fin', 
                'type' => 'DATE',
                'null' => false,
            ]
        ]);

        // Ajouter les colonnes manquantes
        $this->forge->addColumn('reservations', [
            'montant_total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0,
                'after' => 'statut'
            ],
            'motif_annulation' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'montant_total'
            ]
        ]);
    }

    public function down()
    {
        // Supprimer les colonnes ajoutées
        $this->forge->dropColumn('reservations', ['montant_total', 'motif_annulation']);

        // Renommer les colonnes pour revenir à l'état original
        $this->forge->modifyColumn('reservations', [
            'date_debut' => [
                'name' => 'dateDebut',
                'type' => 'DATE',
                'null' => false,
            ],
            'date_fin' => [
                'name' => 'dateFin',
                'type' => 'DATE', 
                'null' => false,
            ]
        ]);
    }
}