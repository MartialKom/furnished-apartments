<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UtilisateurModel;
use App\Models\RoleModel;
use CodeIgniter\HTTP\ResponseInterface;

class UtilisateurController extends BaseController
{
    protected $utilisateurModel;
    protected $roleModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
        $this->roleModel = new RoleModel();
    }

    public function index()
    {
        // Récupérer les utilisateurs avec leurs rôles
        $utilisateurs = $this->utilisateurModel->orderBy('created_at', 'DESC')->findAll();

        // Récupérer tous les rôles pour l'affichage et les formulaires
        $roles = $this->roleModel->getActiveRoles();
        $rolesIndexed = [];
        foreach ($roles as $role) {
            $rolesIndexed[$role['id']] = $role;
        }

        // Ajouter le nom du rôle à chaque utilisateur
        foreach ($utilisateurs as &$utilisateur) {
            if (!empty($utilisateur['role_id']) && isset($rolesIndexed[$utilisateur['role_id']])) {
                $utilisateur['role_nom'] = $rolesIndexed[$utilisateur['role_id']]['nom'];
                $utilisateur['role_code'] = $rolesIndexed[$utilisateur['role_id']]['code'];
            } else {
                // Compatibilité avec l'ancien système (champ role string)
                $utilisateur['role_nom'] = ucfirst($utilisateur['role'] ?? 'Non défini');
                $utilisateur['role_code'] = $utilisateur['role'] ?? '';
            }
        }

        $data = [
            'title' => 'Gestion des Utilisateurs',
            'page_title' => 'Utilisateurs',
            'breadcrumbs' => '<li class="breadcrumb-item">Administration</li><li class="breadcrumb-item active">Utilisateurs</li>',
            'utilisateurs' => $utilisateurs,
            'roles' => $roles
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
            'role_id' => 'required|integer',
            'motDePasse' => 'required|string|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Vérifier que le rôle existe
        $roleId = $this->request->getPost('role_id');
        $role = $this->roleModel->find($roleId);
        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Le rôle sélectionné n\'existe pas.'
            ]);
        }

        $data = [
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'nomUtilisateur' => $this->request->getPost('nomUtilisateur'),
            'telephone' => $this->request->getPost('telephone'),
            'email' => $this->request->getPost('email'),
            'role_id' => $roleId,
            'role' => $role['code'], // Pour compatibilité avec l'ancien système
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
            'role_id' => 'required|integer'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Vérifier que le rôle existe
        $roleId = $this->request->getPost('role_id');
        $role = $this->roleModel->find($roleId);
        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Le rôle sélectionné n\'existe pas.'
            ]);
        }

        $data = [
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'nomUtilisateur' => $this->request->getPost('nomUtilisateur'),
            'telephone' => $this->request->getPost('telephone'),
            'email' => $this->request->getPost('email'),
            'role_id' => $roleId,
            'role' => $role['code'] // Pour compatibilité avec l'ancien système
        ];

        // Ajouter le mot de passe seulement s'il est fourni
        $motDePasse = $this->request->getPost('motDePasse');
        if (!empty($motDePasse)) {
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

        // Empêcher la suppression de l'utilisateur actuellement connecté
        $session = session();
        if ($session->get('user_id') == $id) {
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