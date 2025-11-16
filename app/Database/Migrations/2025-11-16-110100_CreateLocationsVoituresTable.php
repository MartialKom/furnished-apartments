<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLocationsVoituresTable extends Migration
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
            'voiture_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'locataire_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Client enregistré (si existant)'
            ],
            'client_nom' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Nom du client (si nouveau)'
            ],
            'client_email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Email du client (si nouveau)'
            ],
            'client_telephone' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'Téléphone du client (si nouveau)'
            ],
            'client_permis' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Numéro du permis de conduire'
            ],
            'date_debut' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'date_fin_prevue' => [
                'type' => 'DATE',
                'null' => false,
                'comment' => 'Date de fin prévue'
            ],
            'date_fin_reelle' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Date de retour réelle'
            ],
            'nombre_jours' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
                'comment' => 'Nombre de jours de location'
            ],
            'tarif_journalier' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'comment' => 'Tarif au moment de la location'
            ],
            'montant_total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'caution_versee' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0.00,
            ],
            'caution_restituee' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'null' => false,
            ],
            'montant_paye' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'null' => false,
            ],
            'montant_restant' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'null' => false,
            ],
            'kilometrage_depart' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Kilométrage au départ'
            ],
            'kilometrage_retour' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Kilométrage au retour'
            ],
            'etat_depart' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'État du véhicule au départ'
            ],
            'etat_retour' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'État du véhicule au retour'
            ],
            'statut' => [
                'type' => 'ENUM',
                'constraint' => ['en_attente', 'en_cours', 'terminee', 'annulee'],
                'default' => 'en_attente',
                'null' => false,
            ],
            'mode_paiement' => [
                'type' => 'ENUM',
                'constraint' => ['especes', 'virement', 'cheque', 'mobile_money'],
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
                'comment' => 'ID de l\'utilisateur qui a créé'
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
        $this->forge->addForeignKey('voiture_id', 'voitures', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('locataire_id', 'locataires', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('enregistre_par', 'utilisateurs', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('locations_voitures');
    }

    public function down()
    {
        $this->forge->dropTable('locations_voitures');
    }
}
