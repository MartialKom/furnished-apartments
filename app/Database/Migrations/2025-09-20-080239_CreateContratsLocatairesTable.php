<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateContratsLocatairesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'locataire_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'appartement_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'date_debut' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'date_fin' => [
                'type' => 'DATE',
                'null' => true, // null = contrat indéterminé
            ],
            'loyer_mensuel' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'jour_paiement' => [
                'type' => 'TINYINT',
                'constraint' => 2,
                'null' => false,
                'comment' => 'Jour du mois pour le paiement (1-31)'
            ],
            'caution' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'null' => false,
            ],
            'statut' => [
                'type' => 'ENUM',
                'constraint' => ['actif', 'suspendu', 'termine'],
                'default' => 'actif',
                'null' => false,
            ],
            'conditions_particulieres' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('locataire_id', 'locataires', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('appartement_id', 'appartements', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('contrats_locataires');
    }

    public function down()
    {
        $this->forge->dropTable('contrats_locataires');
    }
}