<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\UtilisateurModel;

class TestUserCreation extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'test:user';
    protected $description = 'Test user creation';

    public function run(array $params)
    {
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

        CLI::write('Tentative d\'insertion...', 'yellow');
        CLI::write(json_encode($testData, JSON_PRETTY_PRINT));

        try {
            $result = $utilisateurModel->insert($testData);
            
            if ($result) {
                CLI::write("SUCCESS: Utilisateur créé avec l'ID: " . $result, 'green');
            } else {
                CLI::write("FAILED: Erreurs du modèle:", 'red');
                CLI::write(json_encode($utilisateurModel->errors(), JSON_PRETTY_PRINT));
            }
        } catch (\Exception $e) {
            CLI::write("EXCEPTION: " . $e->getMessage(), 'red');
            CLI::write("Stack trace:", 'red');
            CLI::write($e->getTraceAsString(), 'red');
        }
    }
}