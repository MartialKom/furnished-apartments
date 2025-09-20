<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPersonalInfoToLocataires extends Migration
{
    public function up()
    {
        // Ajouter les nouveaux champs à la table locataires
        $this->forge->addColumn('locataires', [
            'date_naissance' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Date de naissance du locataire'
            ],
            'type_piece' => [
                'type' => 'ENUM',
                'constraint' => ['CNI', 'PASSPORT', 'CARTE_SEJOUR', 'AUTRE'],
                'default' => 'CNI',
                'null' => false,
                'comment' => 'Type de pièce d\'identité'
            ],
            'numero_piece' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'Numéro de la pièce d\'identité'
            ],
            'lieu_naissance' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Lieu de naissance'
            ],
            'nationalite' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => 'Ivoirienne',
                'null' => false,
                'comment' => 'Nationalité du locataire'
            ]
        ]);
    }

    public function down()
    {
        // Supprimer les colonnes ajoutées
        $this->forge->dropColumn('locataires', [
            'date_naissance',
            'type_piece', 
            'numero_piece',
            'lieu_naissance',
            'nationalite'
        ]);
    }
}