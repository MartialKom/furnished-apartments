<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\UtilisateurModel;

class TestUserSession extends BaseCommand
{
    protected $group       = 'app';
    protected $name        = 'test:user-session';
    protected $description = 'Teste la session utilisateur et les rôles.';

    public function run(array $params)
    {
        CLI::write('=== TEST DE LA SESSION UTILISATEUR ===', 'green');
        
        $utilisateurModel = new UtilisateurModel();
        
        // 1. Lister tous les utilisateurs et leurs rôles
        CLI::write("\n1. Utilisateurs dans la base de données:");
        $utilisateurs = $utilisateurModel->findAll();
        
        foreach ($utilisateurs as $utilisateur) {
            CLI::write("  - ID: {$utilisateur['id']} | Nom: {$utilisateur['nom']} {$utilisateur['prenom']} | Rôle: {$utilisateur['role']}");
        }
        
        // 2. Vérifier les rôles disponibles
        CLI::write("\n2. Rôles disponibles:");
        $roles = $utilisateurModel->select('role')->distinct()->findAll();
        foreach ($roles as $role) {
            CLI::write("  - " . $role['role']);
        }
        
        // 3. Simuler une session admin
        CLI::write("\n3. Test de session admin:");
        $admin = $utilisateurModel->where('role', 'admin')->first();
        if ($admin) {
            CLI::write("✓ Admin trouvé: {$admin['nom']} {$admin['prenom']} (ID: {$admin['id']})");
            CLI::write("  Rôle: {$admin['role']}");
        } else {
            CLI::write("✗ Aucun admin trouvé dans la base de données", 'red');
        }
        
        // 4. Vérifier les filtres d'authentification
        CLI::write("\n4. Configuration des filtres:");
        CLI::write("  - Filtre 'auth': Vérifie si l'utilisateur est connecté");
        CLI::write("  - Filtre 'auth:admin': Vérifie si l'utilisateur est admin");
        CLI::write("  - Session doit contenir 'role' = 'admin'");
        
        CLI::write("\n=== FIN DU TEST SESSION ===", 'green');
    }
}

