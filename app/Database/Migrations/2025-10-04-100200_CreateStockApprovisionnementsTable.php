<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockApprovisionnementsTable extends Migration
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
            'prix_unitaire' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'comment'    => 'Prix d\'achat unitaire',
            ],
            'prix_total' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'comment'    => 'Calculé: quantité × prix_unitaire',
            ],
            'fournisseur' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
            ],
            'reference_facture' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'date_approvisionnement' => [
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
        $this->forge->addKey('date_approvisionnement');
        $this->forge->addForeignKey('produit_id', 'stock_produits', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('utilisateur_id', 'utilisateurs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('stock_approvisionnements');
    }

    public function down()
    {
        $this->forge->dropTable('stock_approvisionnements');
    }
}
