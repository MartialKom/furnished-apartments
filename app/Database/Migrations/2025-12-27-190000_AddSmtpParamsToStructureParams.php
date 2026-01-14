<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSmtpParamsToStructureParams extends Migration
{
    public function up()
    {
        // Importer les valeurs depuis .env pour initialisation
        $smtpHost = getenv('SMTP_HOST') ?: 'smtp-fr.securemail.pro';
        $smtpPort = getenv('SMTP_PORT') ?: '465';
        $smtpUser = getenv('SMTP_USER') ?: 'contact@nsenoutower.com';
        $smtpPass = getenv('SMTP_PASS') ?: '';
        $smtpCrypto = getenv('SMTP_CRYPTO') ?: 'ssl';

        // Paramètres SMTP à ajouter
        $params = [
            [
                'param_key' => 'smtp_host',
                'param_value' => $smtpHost,
                'param_type' => 'string',
                'param_group' => 'smtp',
                'description' => 'Serveur SMTP pour l\'envoi d\'emails',
                'is_required' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'param_key' => 'smtp_port',
                'param_value' => $smtpPort,
                'param_type' => 'number',
                'param_group' => 'smtp',
                'description' => 'Port SMTP (465 pour SSL, 587 pour TLS)',
                'is_required' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'param_key' => 'smtp_user',
                'param_value' => $smtpUser,
                'param_type' => 'email',
                'param_group' => 'smtp',
                'description' => 'Utilisateur/Login SMTP',
                'is_required' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'param_key' => 'smtp_pass',
                'param_value' => $smtpPass,
                'param_type' => 'string',
                'param_group' => 'smtp',
                'description' => 'Mot de passe SMTP',
                'is_required' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'param_key' => 'smtp_crypto',
                'param_value' => $smtpCrypto,
                'param_type' => 'string',
                'param_group' => 'smtp',
                'description' => 'Type de chiffrement SMTP (ssl, tls, ou vide)',
                'is_required' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'param_key' => 'smtp_from_email',
                'param_value' => $smtpUser, // Par défaut, même que smtp_user
                'param_type' => 'email',
                'param_group' => 'smtp',
                'description' => 'Email expéditeur qui apparaît dans les emails',
                'is_required' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'param_key' => 'smtp_from_name',
                'param_value' => 'T-Lodge - Système de Gestion',
                'param_type' => 'string',
                'param_group' => 'smtp',
                'description' => 'Nom expéditeur qui apparaît dans les emails',
                'is_required' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'param_key' => 'smtp_timeout',
                'param_value' => '60',
                'param_type' => 'number',
                'param_group' => 'smtp',
                'description' => 'Timeout SMTP en secondes',
                'is_required' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insérer les paramètres SMTP
        $this->db->table('structure_params')->insertBatch($params);
    }

    public function down()
    {
        // Supprimer tous les paramètres SMTP
        $this->db->table('structure_params')
                 ->where('param_group', 'smtp')
                 ->delete();
    }
}
