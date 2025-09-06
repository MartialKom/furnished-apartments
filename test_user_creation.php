<?php

require_once 'vendor/autoload.php';

// Bootstrap CodeIgniter
$app = \CodeIgniter\Config\Services::codeigniter();
$app->initialize();

// Test direct user creation
use App\Models\UtilisateurModel;

$utilisateurModel = new UtilisateurModel();

$testData = [
    'nom' => 'Test',
    'prenom' => 'User',
    'nomUtilisateur' => 'testuser_' . time(),
    'telephone' => '+237600' . time(),
    'email' => 'test@test.com',
    'role' => 'gestionnaire',
    'motDePasse' => 'password123',
    'statut' => 'actif'
];

echo "Tentative d'insertion...\n";
print_r($testData);

try {
    $result = $utilisateurModel->insert($testData);
    
    if ($result) {
        echo "SUCCESS: Utilisateur créé avec l'ID: " . $result . "\n";
    } else {
        echo "FAILED: Erreurs du modèle:\n";
        print_r($utilisateurModel->errors());
    }
} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}