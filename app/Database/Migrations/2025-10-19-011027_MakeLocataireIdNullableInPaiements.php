<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeLocataireIdNullableInPaiements extends Migration
{
    public function up()
    {
        // Supprimer la contrainte de clé étrangère
        $this->forge->dropForeignKey('paiements', 'paiements_locataire_id_foreign');

        // Modifier la colonne pour accepter NULL
        $fields = [
            'locataire_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true, // Maintenant nullable
            ],
        ];

        $this->forge->modifyColumn('paiements', $fields);

        // Recréer la contrainte avec SET NULL
        $this->db->query('ALTER TABLE paiements ADD CONSTRAINT paiements_locataire_id_foreign FOREIGN KEY (locataire_id) REFERENCES locataires(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        // Supprimer la contrainte
        $this->forge->dropForeignKey('paiements', 'paiements_locataire_id_foreign');

        // Remettre NOT NULL
        $fields = [
            'locataire_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
        ];

        $this->forge->modifyColumn('paiements', $fields);

        // Recréer l'ancienne contrainte
        $this->db->query('ALTER TABLE paiements ADD CONSTRAINT paiements_locataire_id_foreign FOREIGN KEY (locataire_id) REFERENCES locataires(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }
}
