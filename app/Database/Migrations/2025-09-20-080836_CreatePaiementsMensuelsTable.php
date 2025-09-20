<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaiementsMensuelsTable extends Migration
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
            'contrat_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'mois_annee' => [
                'type' => 'VARCHAR',
                'constraint' => 7,
                'null' => false,
                'comment' => 'Format: YYYY-MM (ex: 2025-09)'
            ],
            'montant_du' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'montant_paye' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'null' => false,
            ],
            'date_echeance' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'date_paiement' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'statut' => [
                'type' => 'ENUM',
                'constraint' => ['en_attente', 'paye', 'en_retard', 'partiellement_paye'],
                'default' => 'en_attente',
                'null' => false,
            ],
            'nombre_mois_payes' => [
                'type' => 'INT',
                'constraint' => 3,
                'default' => 1,
                'null' => false,
                'comment' => 'Nombre de mois payés en une fois'
            ],
            'mode_paiement' => [
                'type' => 'ENUM',
                'constraint' => ['especes', 'virement', 'cheque', 'mobile_money'],
                'null' => true,
            ],
            'reference_paiement' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'enregistre_par' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'ID de l\'utilisateur qui a enregistré le paiement'
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
        $this->forge->addForeignKey('contrat_id', 'contrats_locataires', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('enregistre_par', 'utilisateurs', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addUniqueKey(['contrat_id', 'mois_annee']);
        $this->forge->createTable('paiements_mensuels');
    }

    public function down()
    {
        $this->forge->dropTable('paiements_mensuels');
    }
}