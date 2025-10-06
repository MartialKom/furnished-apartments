<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockInventaireDetailsTable extends Migration
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
            'inventaire_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'produit_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'stock_theorique' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'comment'    => 'Stock dans le système',
            ],
            'stock_physique' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'comment'    => 'Stock compté réellement',
            ],
            'ecart' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'comment'    => 'stock_physique - stock_theorique',
            ],
            'valeur_ecart' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'comment'    => 'Écart valorisé au prix moyen',
            ],
            'observations' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addKey('inventaire_id');
        $this->forge->addKey('produit_id');
        $this->forge->addForeignKey('inventaire_id', 'stock_inventaires', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('produit_id', 'stock_produits', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('stock_inventaire_details');
    }

    public function down()
    {
        $this->forge->dropTable('stock_inventaire_details');
    }
}
