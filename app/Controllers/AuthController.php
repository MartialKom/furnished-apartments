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
            'title' => 'Connexion - Furnished Apartments',
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
            $sessionData = [
                'user_id' => $user['id'],
                'user_name' => $user['nom'],
                'user_role' => $user['role'],
                'is_logged_in' => true
            ];

            $this->session->set($sessionData);

            return redirect()->to('/admin/dashboard')->with('success', 'Connexion réussie !');
        } else {
            return redirect()->back()->withInput()->with('error', 'Nom d\'utilisateur ou mot de passe incorrect.');
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
