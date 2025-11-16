<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVoituresTable extends Migration
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
            'marque' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
                'comment' => 'Marque du véhicule (ex: Toyota, Peugeot)'
            ],
            'modele' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
                'comment' => 'Modèle du véhicule (ex: Corolla, 308)'
            ],
            'annee' => [
                'type' => 'INT',
                'constraint' => 4,
                'null' => false,
                'comment' => 'Année de fabrication'
            ],
            'immatriculation' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'comment' => 'Plaque d\'immatriculation'
            ],
            'couleur' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'nombre_places' => [
                'type' => 'TINYINT',
                'constraint' => 2,
                'null' => false,
                'default' => 5,
                'comment' => 'Nombre de places'
            ],
            'type_carburant' => [
                'type' => 'ENUM',
                'constraint' => ['essence', 'diesel', 'electrique', 'hybride'],
                'null' => false,
                'default' => 'essence',
            ],
            'transmission' => [
                'type' => 'ENUM',
                'constraint' => ['manuelle', 'automatique'],
                'null' => false,
                'default' => 'manuelle',
            ],
            'kilometrage' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'default' => 0,
                'comment' => 'Kilométrage actuel'
            ],
            'tarif_journalier' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'comment' => 'Prix de location par jour'
            ],
            'caution' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0.00,
                'comment' => 'Montant de la caution'
            ],
            'statut' => [
                'type' => 'ENUM',
                'constraint' => ['disponible', 'louee', 'maintenance', 'indisponible'],
                'default' => 'disponible',
                'null' => false,
            ],
            'photo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Chemin de la photo du véhicule'
            ],
            'numero_chassis' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Numéro de châssis (VIN)'
            ],
            'assurance_expire_le' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Date d\'expiration de l\'assurance'
            ],
            'visite_technique_expire_le' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Date d\'expiration de la visite technique'
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addUniqueKey('immatriculation');
        $this->forge->createTable('voitures');
    }

    public function down()
    {
        $this->forge->dropTable('voitures');
    }
}
