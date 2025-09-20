<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateReservationsDiscountFieldsSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Mettre à jour toutes les réservations existantes avec les nouveaux champs
        $reservations = $db->query("SELECT id, montant_total FROM reservations")->getResultArray();
        
        foreach ($reservations as $reservation) {
            // Initialiser les champs de réduction pour les réservations existantes
            $db->query("UPDATE reservations SET 
                reduction_pourcentage = 0.00, 
                montant_reduction = 0.00, 
                prix_original = montant_total
                WHERE id = ?", [$reservation['id']]);
        }
        
        echo "Toutes les réservations existantes ont été mises à jour avec les nouveaux champs de réduction.\n";
    }
}
