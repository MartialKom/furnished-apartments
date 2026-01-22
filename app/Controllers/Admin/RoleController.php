<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RoleModel;
use App\Models\PermissionModel;
use App\Models\RolePermissionModel;

class RoleController extends BaseController
{
    protected $roleModel;
    protected $permissionModel;
    protected $rolePermissionModel;
    protected $session;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
        $this->permissionModel = new PermissionModel();
        $this->rolePermissionModel = new RolePermissionModel();
        $this->session = session();
    }

    /**
     * Display list of roles
     */
    public function index()
    {
        $roles = $this->roleModel->orderBy('created_at', 'DESC')->findAll();

        // Add users count to each role
        foreach ($roles as &$role) {
            $role['users_count'] = $this->roleModel->getUsersCount($role['id']);
        }

        $data = [
            'title' => 'Gestion des Rôles',
            'page_title' => 'Rôles et Permissions',
            'breadcrumbs' => '<li class="breadcrumb-item">Administration</li><li class="breadcrumb-item active">Rôles</li>',
            'roles' => $roles
        ];

        return view('admin/pages/roles/index', $data);
    }

    /**
     * Show create role form
     */
    public function create()
    {
        $permissions = $this->permissionModel->getGroupedPermissions();

        $data = [
            'title' => 'Créer un Rôle',
            'page_title' => 'Créer un Rôle',
            'breadcrumbs' => '<li class="breadcrumb-item">Administration</li><li class="breadcrumb-item"><a href="/admin/roles">Rôles</a></li><li class="breadcrumb-item active">Créer</li>',
            'permissions' => $permissions,
            'selectedPermissions' => [],
            'role' => null
        ];

        return view('admin/pages/roles/form', $data);
    }

    /**
     * Store new role
     */
    public function store()
    {
        // Clean code first
        $code = $this->request->getPost('code');
        $code = strtolower(trim($code));
        $code = preg_replace('/[^a-z0-9_]/', '_', $code);
        $code = preg_replace('/_+/', '_', $code);
        $code = trim($code, '_');

        log_message('info', "Creating new role - Code: {$code}");

        // Basic validation rules
        $rules = [
            'nom' => 'required|string|max_length[100]',
            'code' => 'required|string|max_length[50]',
            'description' => 'permit_empty|string'
        ];

        // Prepare validation data with cleaned code
        $validationData = [
            'nom' => $this->request->getPost('nom'),
            'code' => $code,
            'description' => $this->request->getPost('description')
        ];

        if (!$this->validate($rules, $validationData)) {
            log_message('error', "Validation failed for new role: " . json_encode($this->validator->getErrors()));
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Check if code is unique
        $existingRole = $this->roleModel->where('code', $code)->first();
        if ($existingRole) {
            log_message('error', "Code '{$code}' already exists");
            return $this->response->setJSON([
                'success' => false,
                'errors' => ['code' => 'Ce code existe déjà.']
            ]);
        }

        $roleData = [
            'nom' => $this->request->getPost('nom'),
            'code' => $code,
            'description' => $this->request->getPost('description'),
            'is_system' => 0,
            'is_super_admin' => 0,
            'statut' => 'actif'
        ];

        log_message('info', "Attempting to create role with data: " . json_encode($roleData));

        // Skip model validation since we already validated in the controller
        $roleId = $this->roleModel->skipValidation(true)->insert($roleData);

        if ($roleId) {
            // Sync permissions
            $permissionIds = $this->request->getPost('permissions') ?? [];
            $this->rolePermissionModel->syncPermissions($roleId, $permissionIds);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Rôle créé avec succès !',
                'redirect' => '/admin/roles'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la création du rôle.',
                'errors' => $this->roleModel->errors()
            ]);
        }
    }

    /**
     * Show edit role form
     */
    public function edit($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/roles')->with('error', 'Rôle non trouvé.');
        }

        $role = $this->roleModel->getRoleWithPermissions($id);
        if (!$role) {
            return redirect()->to('/admin/roles')->with('error', 'Rôle non trouvé.');
        }

        // Prevent editing super admin role
        if ($role['is_super_admin'] == 1) {
            return redirect()->to('/admin/roles')->with('error', 'Le rôle Super Admin ne peut pas être modifié.');
        }

        $permissions = $this->permissionModel->getGroupedPermissions();
        $selectedPermissions = array_column($role['permissions'], 'id');

        $data = [
            'title' => 'Modifier le Rôle',
            'page_title' => 'Modifier le Rôle',
            'breadcrumbs' => '<li class="breadcrumb-item">Administration</li><li class="breadcrumb-item"><a href="/admin/roles">Rôles</a></li><li class="breadcrumb-item active">Modifier</li>',
            'role' => $role,
            'permissions' => $permissions,
            'selectedPermissions' => $selectedPermissions
        ];

        return view('admin/pages/roles/form', $data);
    }

    /**
     * Update role
     */
    public function update($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Rôle non trouvé.'
            ]);
        }

        $role = $this->roleModel->find($id);
        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Rôle non trouvé.'
            ]);
        }

        // Cannot modify super admin role
        if ($role['is_super_admin'] == 1) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Le rôle Super Admin ne peut pas être modifié.'
            ]);
        }

        // Clean code first
        $code = $this->request->getPost('code');
        $codeRaw = $code; // Keep raw for logging
        $code = strtolower(trim($code));
        $code = preg_replace('/[^a-z0-9_]/', '_', $code);
        $code = preg_replace('/_+/', '_', $code);
        $code = trim($code, '_');

        // Clean the existing code from database the same way
        $existingCode = strtolower(trim($role['code']));
        $existingCode = preg_replace('/[^a-z0-9_]/', '_', $existingCode);
        $existingCode = preg_replace('/_+/', '_', $existingCode);
        $existingCode = trim($existingCode, '_');

        log_message('info', "Updating role {$id}");
        log_message('info', "  - Raw POST code: '{$codeRaw}'");
        log_message('info', "  - DB code: '{$role['code']}'");
        log_message('info', "  - Cleaned POST code: '{$code}'");
        log_message('info', "  - Cleaned DB code: '{$existingCode}'");
        log_message('info', "  - Codes match: " . ($code === $existingCode ? 'YES' : 'NO'));

        // Basic validation rules
        $rules = [
            'nom' => 'required|string|max_length[100]',
            'code' => 'required|string|max_length[50]',
            'description' => 'permit_empty|string'
        ];

        // Prepare validation data with cleaned code
        $validationData = [
            'nom' => $this->request->getPost('nom'),
            'code' => $code,
            'description' => $this->request->getPost('description')
        ];

        if (!$this->validate($rules, $validationData)) {
            log_message('error', "Validation failed for role {$id}: " . json_encode($this->validator->getErrors()));
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Check if code has changed and if new code is unique
        if ($code !== $existingCode) {
            log_message('info', "Code has changed, checking uniqueness...");
            $duplicateRole = $this->roleModel->where('code', $code)->where('id !=', $id)->first();
            if ($duplicateRole) {
                log_message('error', "Code '{$code}' already exists for role ID {$duplicateRole['id']}");
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => ['code' => 'Ce code existe déjà.']
                ]);
            }
            log_message('info', "Code is unique, proceeding...");
        } else {
            log_message('info', "Code unchanged, skipping uniqueness check");
        }

        $roleData = [
            'nom' => $this->request->getPost('nom'),
            'code' => $code,
            'description' => $this->request->getPost('description')
        ];

        log_message('info', "Attempting to update role {$id} with data: " . json_encode($roleData));

        // Skip model validation since we already validated in the controller
        if ($this->roleModel->skipValidation(true)->update($id, $roleData)) {
            // Sync permissions
            $permissionIds = $this->request->getPost('permissions') ?? [];
            $syncResult = $this->rolePermissionModel->syncPermissions($id, $permissionIds);

            if (!$syncResult) {
                log_message('error', "Failed to sync permissions for role {$id}");
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Rôle modifié mais erreur lors de la synchronisation des permissions.'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Rôle modifié avec succès !',
                'redirect' => '/admin/roles'
            ]);
        } else {
            $errors = $this->roleModel->errors();
            log_message('error', "Failed to update role {$id}: " . json_encode($errors));

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la modification du rôle.',
                'errors' => $errors
            ]);
        }
    }

    /**
     * Delete role
     */
    public function delete($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Rôle non trouvé.'
            ]);
        }

        $role = $this->roleModel->find($id);
        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Rôle non trouvé.'
            ]);
        }

        // Cannot delete system roles
        if ($role['is_system'] == 1) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Les rôles système ne peuvent pas être supprimés.'
            ]);
        }

        // Check if can delete
        if (!$this->roleModel->canDelete($id)) {
            $usersCount = $this->roleModel->getUsersCount($id);
            return $this->response->setJSON([
                'success' => false,
                'message' => "Ce rôle ne peut pas être supprimé car {$usersCount} utilisateur(s) y sont assignés."
            ]);
        }

        if ($this->roleModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Rôle supprimé avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la suppression du rôle.'
            ]);
        }
    }

    /**
     * Get role data (AJAX)
     */
    public function get($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Rôle non trouvé.'
            ]);
        }

        $role = $this->roleModel->getRoleWithPermissions($id);
        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Rôle non trouvé.'
            ]);
        }

        // Add users count
        $role['users_count'] = $this->roleModel->getUsersCount($id);

        return $this->response->setJSON([
            'success' => true,
            'role' => $role
        ]);
    }

    /**
     * Toggle role status
     */
    public function toggleStatus($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Rôle non trouvé.'
            ]);
        }

        $role = $this->roleModel->find($id);
        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Rôle non trouvé.'
            ]);
        }

        // Cannot deactivate system roles
        if ($role['is_system'] == 1) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Les rôles système ne peuvent pas être désactivés.'
            ]);
        }

        $nouveauStatut = $role['statut'] === 'actif' ? 'inactif' : 'actif';

        if ($this->roleModel->update($id, ['statut' => $nouveauStatut])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Statut modifié avec succès !',
                'nouveau_statut' => $nouveauStatut
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la modification du statut.'
            ]);
        }
    }

    /**
     * Debug - Diagnose roles and permissions issues
     * Access via: /admin/roles/debug
     */
    public function debug()
    {
        $db = \Config\Database::connect();

        // Get all roles with is_super_admin status
        $roles = $db->table('roles')
            ->select('id, nom, code, is_super_admin, is_system, statut')
            ->get()
            ->getResultArray();

        // For each role, get permissions count and users count
        foreach ($roles as &$role) {
            // Count permissions
            $role['permissions_count'] = $db->table('role_permissions')
                ->where('role_id', $role['id'])
                ->countAllResults();

            // Get permission codes
            $permissions = $db->table('role_permissions rp')
                ->select('p.code')
                ->join('permissions p', 'p.id = rp.permission_id')
                ->where('rp.role_id', $role['id'])
                ->get()
                ->getResultArray();
            $role['permission_codes'] = array_column($permissions, 'code');

            // Count users
            $role['users_count'] = $db->table('utilisateurs')
                ->where('role_id', $role['id'])
                ->countAllResults();

            // Get users details
            $users = $db->table('utilisateurs')
                ->select('id, nom, prenom, nomUtilisateur, role, role_id')
                ->where('role_id', $role['id'])
                ->get()
                ->getResultArray();
            $role['users'] = $users;
        }

        // Get total permissions count
        $totalPermissions = $db->table('permissions')->countAllResults();

        // Get all stock-related permissions
        $stockPermissions = $db->table('permissions')
            ->select('id, code, nom')
            ->like('code', 'stock')
            ->orLike('code', 'produits')
            ->orLike('code', 'approvisionnements')
            ->orLike('code', 'sorties')
            ->orLike('code', 'inventaires')
            ->get()
            ->getResultArray();

        // Get current user session data
        $session = session();
        $currentUserDebug = [
            'user_id' => $session->get('user_id'),
            'user_nom' => $session->get('user_nom'),
            'user_role' => $session->get('user_role'),
            'user_role_id' => $session->get('user_role_id'),
            'user_role_name' => $session->get('user_role_name'),
            'is_super_admin' => $session->get('is_super_admin'),
            'user_permissions' => $session->get('user_permissions')
        ];

        // Get users with old role field but no role_id
        $usersWithoutRoleId = $db->table('utilisateurs')
            ->select('id, nom, prenom, nomUtilisateur, role, role_id')
            ->where('role_id IS NULL OR role_id = 0')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Debug Rôles & Permissions',
            'page_title' => 'Diagnostic Rôles & Permissions',
            'breadcrumbs' => '<li class="breadcrumb-item">Administration</li><li class="breadcrumb-item active">Debug</li>',
            'roles' => $roles,
            'totalPermissions' => $totalPermissions,
            'stockPermissions' => $stockPermissions,
            'currentUserDebug' => $currentUserDebug,
            'usersWithoutRoleId' => $usersWithoutRoleId
        ];

        return view('admin/pages/roles/debug', $data);
    }
}
