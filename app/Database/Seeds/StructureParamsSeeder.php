<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StructureParamsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Informations générales de la structure
            [
                'param_key' => 'structure_name',
                'param_value' => 'APPARTEMENTS MEUBLES',
                'param_type' => 'string',
                'param_group' => 'structure',
                'description' => 'Nom de la structure/entreprise',
                'is_required' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'param_key' => 'structure_address',
                'param_value' => 'Abidjan, Cocody Angré',
                'param_type' => 'text',
                'param_group' => 'structure',
                'description' => 'Adresse complète de la structure',
                'is_required' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'param_key' => 'structure_phone',
                'param_value' => '+225 07 07 07 07 07',
                'param_type' => 'phone',
                'param_group' => 'structure',
                'description' => 'Numéro de téléphone principal',
                'is_required' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'param_key' => 'structure_email',
                'param_value' => 'contact@appartementsmeubles.com',
                'param_type' => 'email',
                'param_group' => 'structure',
                'description' => 'Adresse email de contact',
                'is_required' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'param_key' => 'structure_website',
                'param_value' => 'www.appartementsmeubles.com',
                'param_type' => 'url',
                'param_group' => 'structure',
                'description' => 'Site web de la structure',
                'is_required' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'param_key' => 'structure_logo',
                'param_value' => '',
                'param_type' => 'string',
                'param_group' => 'structure',
                'description' => 'Chemin vers le logo de la structure',
                'is_required' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Informations légales
            [
                'param_key' => 'structure_rc',
                'param_value' => 'RC-ABJ-2024-A-12345',
                'param_type' => 'string',
                'param_group' => 'legal',
                'description' => 'Numéro de registre du commerce',
                'is_required' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'param_key' => 'structure_nif',
                'param_value' => 'NIF-ABJ-2024-67890',
                'param_type' => 'string',
                'param_group' => 'legal',
                'description' => 'Numéro d\'identification fiscale',
                'is_required' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'param_key' => 'structure_legal_form',
                'param_value' => 'SARL',
                'param_type' => 'string',
                'param_group' => 'legal',
                'description' => 'Forme juridique de la société',
                'is_required' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Paramètres par défaut
            [
                'param_key' => 'default_nationality',
                'param_value' => 'Ivoirienne',
                'param_type' => 'string',
                'param_group' => 'defaults',
                'description' => 'Nationalité par défaut pour les nouveaux locataires',
                'is_required' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'param_key' => 'default_id_type',
                'param_value' => 'CNI',
                'param_type' => 'string',
                'param_group' => 'defaults',
                'description' => 'Type de pièce d\'identité par défaut',
                'is_required' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('structure_params')->insertBatch($data);
    }
}