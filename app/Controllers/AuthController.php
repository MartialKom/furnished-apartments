<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UtilisateurModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    protected $session;
    protected $utilisateurModel;

    public function __construct()
    {
        $this->session = session();
        $this->utilisateurModel = new UtilisateurModel();
    }

    public function login()
    {
        if ($this->session->get('user_id')) {
            return redirect()->to('/admin/dashboard');
        }

        $data = [
            'title' => 'Connexion - T-Lodge',
            'description' => 'Connexion pour les gestionnaires et administrateurs'
        ];

        return view('auth/login', $data);
    }

    public function attemptLogin()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->utilisateurModel->authentifier($username, $password);

        if ($user) {
            // Check if user has a role assigned
            if (empty($user['role_id']) && empty($user['role'])) {
                return redirect()->back()->withInput()->with('error', 'Votre compte n\'a pas de rôle assigné. Contactez l\'administrateur.');
            }

            // Get user with role and permissions
            $userWithPermissions = $this->utilisateurModel->getUserWithPermissions($user['id']);

            // Check if role exists and is active
            if (!empty($userWithPermissions['role_data'])) {
                if ($userWithPermissions['role_data']['statut'] === 'inactif') {
                    return redirect()->back()->withInput()->with('error', 'Votre rôle a été désactivé. Contactez l\'administrateur.');
                }
            }

            $sessionData = [
                'user_id' => $user['id'],
                'user_nom' => $user['nom'],
                'user_prenom' => $user['prenom'],
                'user_nom_utilisateur' => $user['nomUtilisateur'],
                'user_telephone' => $user['telephone'],
                'user_email' => $user['email'],
                'user_role' => $user['role'], // Kept for backward compatibility
                'user_role_id' => $user['role_id'] ?? null,
                'user_role_name' => $userWithPermissions['role_data']['nom'] ?? '',
                'user_permissions' => $userWithPermissions['permissions'] ?? [],
                'is_super_admin' => ($userWithPermissions['role_data']['is_super_admin'] ?? 0) == 1,
                'is_logged_in' => true
            ];

            $this->session->set($sessionData);

            log_message('info', "User {$user['id']} ({$user['nomUtilisateur']}) logged in with role: " . ($userWithPermissions['role_data']['nom'] ?? 'N/A'));

            return redirect()->to('/admin/dashboard')->with('success', 'Connexion réussie !');
        } else {
            return redirect()->back()->withInput()->with('error', 'Nom d\'utilisateur ou mot de passe incorrect.');
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/admin/login')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
