<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockProduitsTable extends Migration
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
            'categorie_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'nom' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'unite_mesure' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'comment'    => 'Ex: pièce, litre, kg, boîte, etc.',
            ],
            'stock_minimum' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
                'comment'    => 'Seuil d\'alerte',
            ],
            'stock_actuel' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
                'comment'    => 'Stock disponible calculé',
            ],
            'prix_moyen' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
                'comment'    => 'Prix moyen pondéré',
            ],
            'actif' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1=actif, 0=inactif',
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
        $this->forge->addKey('categorie_id');
        $this->forge->addForeignKey('categorie_id', 'stock_categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('stock_produits');
    }

    public function down()
    {
        $this->forge->dropTable('stock_produits');
    }
}
