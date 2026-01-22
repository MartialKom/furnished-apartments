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
        if ($this->session->get('is_logged_in')) {
            // Rediriger vers la première page accessible
            $sessionData = [
                'is_super_admin' => $this->session->get('is_super_admin'),
                'user_permissions' => $this->session->get('user_permissions') ?? []
            ];
            return redirect()->to($this->getFirstAccessiblePage($sessionData));
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

            // Rediriger vers la première page accessible
            $redirectUrl = $this->getFirstAccessiblePage($sessionData);
            return redirect()->to($redirectUrl)->with('success', 'Connexion réussie !');
        } else {
            return redirect()->back()->withInput()->with('error', 'Nom d\'utilisateur ou mot de passe incorrect.');
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/admin/login')->with('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Trouve la première page accessible pour l'utilisateur après login
     */
    protected function getFirstAccessiblePage(array $sessionData): string
    {
        // Super admin va toujours au dashboard
        if ($sessionData['is_super_admin'] ?? false) {
            return '/admin/dashboard';
        }

        $permissions = $sessionData['user_permissions'] ?? [];

        // Ordre de priorité des pages à vérifier
        $pagePermissions = [
            '/admin/dashboard' => 'view_dashboard',
            '/admin/stock' => 'view_stock_dashboard',
            '/admin/stock/produits' => 'view_produits',
            '/admin/appartements' => 'view_appartements',
            '/admin/reservations' => 'view_reservations',
            '/admin/voitures' => 'view_voitures',
            '/admin/locataires' => 'view_locataires',
            '/admin/paiements' => 'view_paiements',
            '/admin/rapports' => 'view_rapports',
        ];

        foreach ($pagePermissions as $url => $permission) {
            if (in_array($permission, $permissions)) {
                return $url;
            }
        }

        // Si aucune page accessible, page d'erreur
        return '/admin/access-denied';
    }
}
