<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateUtilisateursNotificationFields extends Seeder
{
    public function run()
    {
        // Mettre à jour tous les utilisateurs existants avec les valeurs par défaut
        $this->db->query("UPDATE utilisateurs SET notifications_email = 1, heure_notification = '09:00:00' WHERE notifications_email IS NULL");
        
        echo "✓ Champs de notification mis à jour pour tous les utilisateurs\n";
    }
}