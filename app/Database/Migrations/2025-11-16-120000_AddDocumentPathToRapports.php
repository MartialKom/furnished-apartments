<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDocumentPathToRapports extends Migration
{
    public function up()
    {
        $this->forge->addColumn('rapports', [
            'document_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'contenu'
            ],
            'document_nom_original' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'document_path'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('rapports', ['document_path', 'document_nom_original']);
    }
}
