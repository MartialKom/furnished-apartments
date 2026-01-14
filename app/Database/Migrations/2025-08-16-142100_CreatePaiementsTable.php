<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaiementsTable extends Migration
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
            'montant' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'statut' => [
                'type' => 'ENUM',
                'constraint' => ['en_attente', 'paye', 'rembourse', 'annule'],
                'default' => 'en_attente',
                'null' => false,
            ],
            'locataire_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'reservation_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
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
        $this->forge->addForeignKey('reservation_id', 'reservations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('paiements');
    }

    public function down()
    {
        $this->forge->dropTable('paiements');
    }
}
