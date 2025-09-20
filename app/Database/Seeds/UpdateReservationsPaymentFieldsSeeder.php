<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateReservationsPaymentFieldsSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Mettre à jour toutes les réservations existantes
        $reservations = $db->query("SELECT id, montant_total FROM reservations")->getResultArray();
        
        foreach ($reservations as $reservation) {
            // Initialiser les champs de paiement pour les réservations existantes
            $db->query("UPDATE reservations SET 
                montant_paye = 0, 
                montant_restant = montant_total, 
                type_reservation = 'en_ligne',
                notes = ''
                WHERE id = ?", [$reservation['id']]);
        }
        
        echo "Toutes les réservations existantes ont été mises à jour avec les nouveaux champs de paiement.\n";
    }
}
