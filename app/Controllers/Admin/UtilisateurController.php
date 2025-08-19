<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UtilisateurModel;
use CodeIgniter\HTTP\ResponseInterface;

class UtilisateurController extends BaseController
{
    protected $utilisateurModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
    }

    public function index()
    {
        $utilisateurs = $this->utilisateurModel->findAll();
        
        $data = [
            'title' => 'Gestion des Utilisateurs',
            'page_title' => 'Utilisateurs',
            'breadcrumbs' => '<li class="breadcrumb-item">Administration</li><li class="breadcrumb-item active">Utilisateurs</li>',
            'utilisateurs' => $utilisateurs
        ];

        return view('admin/pages/utilisateurs/index', $data);
    }

    public function create()
    {
        $rules = [
            'nom' => 'required|string|max_length[100]',
            'prenom' => 'required|string|max_length[100]',
            'nomUtilisateur' => 'required|string|max_length[50]|is_unique[utilisateurs.nomUtilisateur]',
            'telephone' => 'required|string|max_length[20]|is_unique[utilisateurs.telephone]',
            'email' => 'permit_empty|valid_email|max_length[150]',
            'role' => 'required|in_list[admin,gestionnaire]',
            'motDePasse' => 'required|string|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'nomUtilisateur' => $this->request->getPost('nomUtilisateur'),
            'telephone' => $this->request->getPost('telephone'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'motDePasse' => $this->request->getPost('motDePasse'),
            'statut' => 'actif'
        ];

        if ($this->utilisateurModel->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Utilisateur créé avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'utilisateur.'
            ]);
        }
    }

    public function update($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Utilisateur non trouvé.'
            ]);
        }

        $utilisateur = $this->utilisateurModel->find($id);
        if (!$utilisateur) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Utilisateur non trouvé.'
            ]);
        }

        $rules = [
            'nom' => 'required|string|max_length[100]',
            'prenom' => 'required|string|max_length[100]',
            'nomUtilisateur' => "required|string|max_length[50]|is_unique[utilisateurs.nomUtilisateur,id,{$id}]",
            'telephone' => "required|string|max_length[20]|is_unique[utilisateurs.telephone,id,{$id}]",
            'email' => 'permit_empty|valid_email|max_length[150]',
            'role' => 'required|in_list[admin,gestionnaire]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'nomUtilisateur' => $this->request->getPost('nomUtilisateur'),
            'telephone' => $this->request->getPost('telephone'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role')
        ];

        // Mettre à jour le mot de passe seulement s'il est fourni
        $motDePasse = $this->request->getPost('motDePasse');
        if (!empty($motDePasse)) {
            if (strlen($motDePasse) < 6) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Le mot de passe doit contenir au moins 6 caractères.'
                ]);
            }
            $data['motDePasse'] = $motDePasse;
        }

        if ($this->utilisateurModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Utilisateur modifié avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la modification de l\'utilisateur.'
            ]);
        }
    }

    public function toggleStatus($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Utilisateur non trouvé.'
            ]);
        }

        $utilisateur = $this->utilisateurModel->find($id);
        if (!$utilisateur) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Utilisateur non trouvé.'
            ]);
        }

        // Ne pas permettre de désactiver le dernier admin
        if ($utilisateur['role'] === 'admin' && $utilisateur['statut'] === 'actif') {
            $nombreAdminsActifs = $this->utilisateurModel->where('role', 'admin')
                                                       ->where('statut', 'actif')
                                                       ->countAllResults();
            if ($nombreAdminsActifs <= 1) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Impossible de désactiver le dernier administrateur actif.'
                ]);
            }
        }

        $nouveauStatut = $utilisateur['statut'] === 'actif' ? 'inactif' : 'actif';
        
        if ($this->utilisateurModel->update($id, ['statut' => $nouveauStatut])) {
            $action = $nouveauStatut === 'actif' ? 'activé' : 'désactivé';
            return $this->response->setJSON([
                'success' => true,
                'message' => "Utilisateur {$action} avec succès !",
                'nouveau_statut' => $nouveauStatut
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la modification du statut.'
            ]);
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Utilisateur non trouvé.'
            ]);
        }

        $utilisateur = $this->utilisateurModel->find($id);
        if (!$utilisateur) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Utilisateur non trouvé.'
            ]);
        }

        // Ne pas permettre de supprimer le dernier admin
        if ($utilisateur['role'] === 'admin') {
            $nombreAdmins = $this->utilisateurModel->where('role', 'admin')->countAllResults();
            if ($nombreAdmins <= 1) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Impossible de supprimer le dernier administrateur.'
                ]);
            }
        }

        // Ne pas permettre de se supprimer soi-même
        if ($id == session()->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vous ne pouvez pas supprimer votre propre compte.'
            ]);
        }

        if ($this->utilisateurModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'utilisateur.'
            ]);
        }
    }

    public function get($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Utilisateur non trouvé.'
            ]);
        }

        $utilisateur = $this->utilisateurModel->find($id);
        if (!$utilisateur) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Utilisateur non trouvé.'
            ]);
        }

        // Ne pas retourner le mot de passe
        unset($utilisateur['motDePasse']);

        return $this->response->setJSON([
            'success' => true,
            'utilisateur' => $utilisateur
        ]);
    }
}