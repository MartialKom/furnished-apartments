<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReservationsTable extends Migration
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
            'dateDebut' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'dateFin' => [
                'type' => 'DATE',
                'null' => false,
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
            'statut' => [
                'type' => 'ENUM',
                'constraint' => ['en_attente', 'confirmee', 'annulee', 'terminee'],
                'default' => 'en_attente',
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
        $this->forge->addForeignKey('appartement_id', 'appartements', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('reservations');
    }

    public function down()
    {
        $this->forge->dropTable('reservations');
    }
}
