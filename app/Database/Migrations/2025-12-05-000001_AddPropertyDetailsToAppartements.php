<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPropertyDetailsToAppartements extends Migration
{
    public function up()
    {
        // Modifier la colonne type pour inclure les nouveaux types
        $this->db->query("ALTER TABLE `appartements`
            MODIFY COLUMN `type` ENUM('meuble','non_meuble') NOT NULL DEFAULT 'meuble'");

        // Ajouter les nouvelles colonnes
        $fields = [
            'categorie' => [
                'type' => 'ENUM',
                'constraint' => ['appartement', 'logement', 'boutique'],
                'default' => 'appartement',
                'null' => false,
                'after' => 'type'
            ],
            'nombre_chambres' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Nombre de chambres (2 ou 3)',
                'after' => 'categorie'
            ],
            'superficie' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'comment' => 'Superficie en m²',
                'after' => 'nombre_chambres'
            ],
            'numero_bien' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'comment' => 'Numéro/Référence du bien',
                'after' => 'superficie'
            ],
            'etage' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
                'comment' => 'Numéro d\'étage',
                'after' => 'numero_bien'
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Description détaillée du bien',
                'after' => 'etage'
            ]
        ];

        $this->forge->addColumn('appartements', $fields);

        // Créer un index pour la recherche rapide par catégorie
        $this->db->query("CREATE INDEX idx_categorie ON appartements(categorie)");
        $this->db->query("CREATE INDEX idx_nombre_chambres ON appartements(nombre_chambres)");
    }

    public function down()
    {
        // Supprimer les index
        $this->db->query("DROP INDEX idx_categorie ON appartements");
        $this->db->query("DROP INDEX idx_nombre_chambres ON appartements");

        // Supprimer les colonnes ajoutées
        $this->forge->dropColumn('appartements', [
            'categorie',
            'nombre_chambres',
            'superficie',
            'numero_bien',
            'etage',
            'description'
        ]);

        // Restaurer l'ancienne définition de la colonne type
        $this->db->query("ALTER TABLE `appartements`
            MODIFY COLUMN `type` ENUM('meuble','non_meuble') NOT NULL DEFAULT 'meuble'");
    }
}
