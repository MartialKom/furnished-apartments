<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFacturesEauTable extends Migration
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
                'comment' => 'Format: YYYY-MM (ex: 2025-11)'
            ],
            'montant' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'consommation_m3' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'comment' => 'Consommation en m³'
            ],
            'index_precedent' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'comment' => 'Index compteur du mois précédent'
            ],
            'index_actuel' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'comment' => 'Index compteur actuel'
            ],
            'date_emission' => [
                'type' => 'DATE',
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
            'montant_paye' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'null' => false,
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
                'comment' => 'ID de l\'utilisateur qui a enregistré la facture'
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
        $this->forge->addUniqueKey(['contrat_id', 'mois_annee'], 'unique_facture_mois');
        $this->forge->createTable('factures_eau');
    }

    public function down()
    {
        $this->forge->dropTable('factures_eau');
    }
}
