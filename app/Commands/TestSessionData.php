<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestSessionData extends BaseCommand
{
    protected $group       = 'app';
    protected $name        = 'test:session-data';
    protected $description = 'Teste les données de session pour debug.';

    public function run(array $params)
    {
        CLI::write('=== TEST DES DONNÉES DE SESSION ===', 'green');
        
        // Simuler une session
        $session = \Config\Services::session();
        
        CLI::write("\n1. Données de session actuelles:");
        
        $sessionData = $session->get();
        if (empty($sessionData)) {
            CLI::write("  Aucune donnée de session trouvée", 'yellow');
        } else {
            foreach ($sessionData as $key => $value) {
                CLI::write("  - {$key}: {$value}");
            }
        }
        
        CLI::write("\n2. Clés de session importantes:");
        CLI::write("  - is_logged_in: " . ($session->get('is_logged_in') ? 'true' : 'false'));
        CLI::write("  - user_id: " . ($session->get('user_id') ?? 'null'));
        CLI::write("  - user_role: " . ($session->get('user_role') ?? 'null'));
        CLI::write("  - user_nom: " . ($session->get('user_nom') ?? 'null'));
        
        CLI::write("\n3. Test de récupération du rôle:");
        $role = $session->get('user_role');
        if ($role === 'admin') {
            CLI::write("✓ Rôle admin détecté", 'green');
        } elseif ($role === 'gestionnaire') {
            CLI::write("✓ Rôle gestionnaire détecté", 'yellow');
        } elseif ($role) {
            CLI::write("? Rôle inconnu: {$role}", 'yellow');
        } else {
            CLI::write("✗ Aucun rôle trouvé", 'red');
        }
        
        CLI::write("\n=== FIN DU TEST SESSION ===", 'green');
    }
}

