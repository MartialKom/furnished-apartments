<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockInventairesTable extends Migration
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
            'numero_inventaire' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
                'comment'    => 'Format: INV-YYYYMMDD-XXX',
            ],
            'date_inventaire' => [
                'type' => 'DATE',
            ],
            'statut' => [
                'type'       => 'ENUM',
                'constraint' => ['en_cours', 'termine', 'valide'],
                'default'    => 'en_cours',
            ],
            'observations' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'utilisateur_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'Utilisateur responsable',
            ],
            'valide_par' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Utilisateur qui a validé',
            ],
            'date_validation' => [
                'type' => 'DATETIME',
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
        $this->forge->addKey('date_inventaire');
        $this->forge->addForeignKey('utilisateur_id', 'utilisateurs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('valide_par', 'utilisateurs', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('stock_inventaires');
    }

    public function down()
    {
        $this->forge->dropTable('stock_inventaires');
    }
}
