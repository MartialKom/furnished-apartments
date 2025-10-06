<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockSortiesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'produit_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'quantite' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'appartement_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Appartement de destination',
            ],
            'destination' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
                'comment'    => 'Autre destination si non appartement',
            ],
            'motif' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'date_sortie' => [
                'type' => 'DATE',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'utilisateur_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'Utilisateur qui a enregistré',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('produit_id');
        $this->forge->addKey('appartement_id');
        $this->forge->addKey('date_sortie');
        $this->forge->addForeignKey('produit_id', 'stock_produits', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('appartement_id', 'appartements', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('utilisateur_id', 'utilisateurs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('stock_sorties');
    }

    public function down()
    {
        $this->forge->dropTable('stock_sorties');
    }
}
