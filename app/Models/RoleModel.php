<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table            = 'roles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nom', 'code', 'description', 'is_system', 'is_super_admin', 'statut'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nom' => 'required|string|max_length[100]',
        'code' => 'required|string|max_length[50]|is_unique[roles.code,id,{id}]',
        'description' => 'permit_empty|string',
        'statut' => 'permit_empty|in_list[actif,inactif]'
    ];

    protected $validationMessages = [
        'nom' => [
            'required' => 'Le nom du rôle est obligatoire.',
            'max_length' => 'Le nom ne peut pas dépasser 100 caractères.'
        ],
        'code' => [
            'required' => 'Le code du rôle est obligatoire.',
            'is_unique' => 'Ce code existe déjà.',
            'max_length' => 'Le code ne peut pas dépasser 50 caractères.'
        ]
    ];

    /**
     * Get role with its permissions
     *
     * @param int $roleId The role ID
     * @return array|null Role data with permissions array, or null if not found
     */
    public function getRoleWithPermissions(int $roleId): ?array
    {
        $role = $this->find($roleId);
        if (!$role) {
            return null;
        }

        $db = \Config\Database::connect();
        $permissions = $db->table('role_permissions rp')
            ->select('p.id, p.nom, p.code, p.module, p.groupe, p.description')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->where('rp.role_id', $roleId)
            ->orderBy('p.groupe, p.ordre')
            ->get()
            ->getResultArray();

        $role['permissions'] = $permissions;
        return $role;
    }

    /**
     * Check if role can be deleted
     *
     * @param int $roleId The role ID
     * @return bool True if role can be deleted, false otherwise
     */
    public function canDelete(int $roleId): bool
    {
        $role = $this->find($roleId);
        if (!$role) {
            return false;
        }

        // Cannot delete system roles
        if ($role['is_system'] == 1) {
            return false;
        }

        // Check if users are assigned to this role
        $utilisateurModel = new UtilisateurModel();
        $usersCount = $utilisateurModel->where('role_id', $roleId)->countAllResults();

        return $usersCount == 0;
    }

    /**
     * Get all active roles
     *
     * @return array Array of active roles
     */
    public function getActiveRoles(): array
    {
        return $this->where('statut', 'actif')->orderBy('nom', 'ASC')->findAll();
    }

    /**
     * Get users count for a role
     *
     * @param int $roleId The role ID
     * @return int Number of users assigned to this role
     */
    public function getUsersCount(int $roleId): int
    {
        $utilisateurModel = new UtilisateurModel();
        return $utilisateurModel->where('role_id', $roleId)->countAllResults();
    }

    /**
     * Get role by code
     *
     * @param string $code The role code
     * @return array|null Role data or null if not found
     */
    public function getRoleByCode(string $code): ?array
    {
        return $this->where('code', $code)->first();
    }
}
