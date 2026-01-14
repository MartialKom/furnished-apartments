<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AppartementSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'adresse' => 'Appartement A101 - Dragage, 1er étage',
                'photos' => json_encode([
                    'assets/frontend/images/apartments/apt1_1.jpg',
                    'assets/frontend/images/apartments/apt1_2.jpg',
                    'assets/frontend/images/apartments/apt1_3.jpg'
                ]),
                'equipements' => json_encode([
                    'Climatisation',
                    'WiFi haut débit',
                    'Télévision satellite',
                    'Cuisine équipée',
                    'Réfrigérateur',
                    'Machine à laver',
                    'Balcon',
                    'Parking'
                ]),
                'tarifs' => 25000.00,
                'statut' => 'disponible'
            ],
            [
                'adresse' => 'Studio B202 - Dragage, 2ème étage',
                'photos' => json_encode([
                    'assets/frontend/images/apartments/studio1_1.jpg',
                    'assets/frontend/images/apartments/studio1_2.jpg'
                ]),
                'equipements' => json_encode([
                    'Climatisation',
                    'WiFi haut débit',
                    'Kitchenette équipée',
                    'Réfrigérateur',
                    'Télévision',
                    'Salle de bain privée'
                ]),
                'tarifs' => 20000.00,
                'statut' => 'disponible'
            ],
            [
                'adresse' => 'Appartement C301 - Dragage, 3ème étage',
                'photos' => json_encode([
                    'assets/frontend/images/apartments/apt2_1.jpg',
                    'assets/frontend/images/apartments/apt2_2.jpg',
                    'assets/frontend/images/apartments/apt2_3.jpg'
                ]),
                'equipements' => json_encode([
                    'Climatisation',
                    'WiFi haut débit',
                    'Télévision satellite',
                    'Cuisine moderne équipée',
                    'Réfrigérateur',
                    'Machine à laver',
                    'Lave-vaisselle',
                    'Grand balcon',
                    'Parking sécurisé',
                    'Vue panoramique'
                ]),
                'tarifs' => 35000.00,
                'statut' => 'disponible'
            ],
            [
                'adresse' => 'Studio D102 - Dragage, 1er étage',
                'photos' => json_encode([
                    'assets/frontend/images/apartments/studio2_1.jpg',
                    'assets/frontend/images/apartments/studio2_2.jpg'
                ]),
                'equipements' => json_encode([
                    'Climatisation',
                    'WiFi',
                    'Kitchenette',
                    'Réfrigérateur',
                    'Télévision',
                    'Salle de bain'
                ]),
                'tarifs' => 18000.00,
                'statut' => 'occupe'
            ],
            [
                'adresse' => 'Appartement E401 - Dragage, 4ème étage',
                'photos' => json_encode([
                    'assets/frontend/images/apartments/apt3_1.jpg',
                    'assets/frontend/images/apartments/apt3_2.jpg',
                    'assets/frontend/images/apartments/apt3_3.jpg'
                ]),
                'equipements' => json_encode([
                    'Climatisation',
                    'WiFi haut débit',
                    'Télévision satellite',
                    'Cuisine américaine équipée',
                    'Réfrigérateur',
                    'Machine à laver',
                    'Micro-ondes',
                    'Balcon avec vue',
                    'Parking',
                    'Ascenseur'
                ]),
                'tarifs' => 30000.00,
                'statut' => 'disponible'
            ],
            [
                'adresse' => 'Studio F203 - Dragage, 2ème étage',
                'photos' => json_encode([
                    'assets/frontend/images/apartments/studio3_1.jpg',
                    'assets/frontend/images/apartments/studio3_2.jpg'
                ]),
                'equipements' => json_encode([
                    'Climatisation',
                    'WiFi',
                    'Kitchenette moderne',
                    'Réfrigérateur',
                    'Télévision écran plat',
                    'Salle de bain équipée',
                    'Balcon'
                ]),
                'tarifs' => 22000.00,
                'statut' => 'disponible'
            ],
            [
                'adresse' => 'Appartement G501 - Dragage, 5ème étage (Penthouse)',
                'photos' => json_encode([
                    'assets/frontend/images/apartments/penthouse_1.jpg',
                    'assets/frontend/images/apartments/penthouse_2.jpg',
                    'assets/frontend/images/apartments/penthouse_3.jpg',
                    'assets/frontend/images/apartments/penthouse_4.jpg'
                ]),
                'equipements' => json_encode([
                    'Climatisation centrale',
                    'WiFi haut débit',
                    'Télévision satellite premium',
                    'Cuisine luxueuse équipée',
                    'Réfrigérateur américain',
                    'Machine à laver/sécher',
                    'Lave-vaisselle',
                    'Terrasse panoramique',
                    'Parking privé',
                    'Ascenseur',
                    'Sécurité 24h/24',
                    'Jacuzzi'
                ]),
                'tarifs' => 50000.00,
                'statut' => 'disponible'
            ],
            [
                'adresse' => 'Appartement H302 - Dragage, 3ème étage',
                'photos' => json_encode([
                    'assets/frontend/images/apartments/apt4_1.jpg',
                    'assets/frontend/images/apartments/apt4_2.jpg'
                ]),
                'equipements' => json_encode([
                    'Climatisation',
                    'WiFi',
                    'Télévision',
                    'Cuisine équipée',
                    'Réfrigérateur',
                    'Balcon',
                    'Parking'
                ]),
                'tarifs' => 28000.00,
                'statut' => 'maintenance'
            ],
            [
                'adresse' => 'Studio I204 - Dragage, 2ème étage',
                'photos' => json_encode([
                    'assets/frontend/images/apartments/studio4_1.jpg',
                    'assets/frontend/images/apartments/studio4_2.jpg'
                ]),
                'equipements' => json_encode([
                    'Climatisation',
                    'WiFi haut débit',
                    'Kitchenette',
                    'Réfrigérateur',
                    'Télévision',
                    'Salle de bain moderne'
                ]),
                'tarifs' => 19500.00,
                'statut' => 'disponible'
            ],
            [
                'adresse' => 'Appartement J402 - Dragage, 4ème étage',
                'photos' => json_encode([
                    'assets/frontend/images/apartments/apt5_1.jpg',
                    'assets/frontend/images/apartments/apt5_2.jpg',
                    'assets/frontend/images/apartments/apt5_3.jpg'
                ]),
                'equipements' => json_encode([
                    'Climatisation',
                    'WiFi haut débit',
                    'Télévision satellite',
                    'Cuisine complète équipée',
                    'Réfrigérateur',
                    'Machine à laver',
                    'Micro-ondes',
                    'Grand balcon',
                    'Parking',
                    'Vue dégagée'
                ]),
                'tarifs' => 32000.00,
                'statut' => 'disponible'
            ]
        ];

        // Utilisation du modèle pour insérer les données
        $appartementModel = new \App\Models\AppartementModel();
        
        foreach ($data as $appartementData) {
            // Vérifier si l'appartement existe déjà
            $existingAppartement = $appartementModel->where('adresse', $appartementData['adresse'])->first();
            
            if (!$existingAppartement) {
                $appartementModel->insert($appartementData);
                echo "Appartement '{$appartementData['adresse']}' créé avec succès (Tarif: {$appartementData['tarifs']} FCFA - Statut: {$appartementData['statut']}).\n";
            } else {
                echo "Appartement '{$appartementData['adresse']}' existe déjà.\n";
            }
        }
        
        echo "\n=== Résumé des appartements ===\n";
        $disponibles = $appartementModel->where('statut', 'disponible')->countAllResults();
        $occupes = $appartementModel->where('statut', 'occupe')->countAllResults();
        $maintenance = $appartementModel->where('statut', 'maintenance')->countAllResults();
        
        echo "Appartements disponibles: {$disponibles}\n";
        echo "Appartements occupés: {$occupes}\n";
        echo "Appartements en maintenance: {$maintenance}\n";
        echo "Total: " . ($disponibles + $occupes + $maintenance) . "\n";
    }
}