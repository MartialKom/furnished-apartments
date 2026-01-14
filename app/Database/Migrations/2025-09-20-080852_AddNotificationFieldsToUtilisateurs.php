<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNotificationFieldsToUtilisateurs extends Migration
{
    public function up()
    {
        $this->forge->addColumn('utilisateurs', [
            'notifications_email' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'null' => false,
                'after' => 'statut',
                'comment' => 'Recevoir les notifications par email'
            ],
            'heure_notification' => [
                'type' => 'TIME',
                'default' => '09:00:00',
                'null' => true,
                'after' => 'notifications_email',
                'comment' => 'Heure préférée pour recevoir les notifications'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('utilisateurs', ['notifications_email', 'heure_notification']);
    }
}