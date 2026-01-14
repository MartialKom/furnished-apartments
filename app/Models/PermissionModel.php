<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissionModel extends Model
{
    protected $table            = 'permissions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nom', 'code', 'description', 'module', 'groupe', 'ordre'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nom' => 'required|string|max_length[100]',
        'code' => 'required|string|max_length[100]|is_unique[permissions.code,id,{id}]',
        'module' => 'required|string|max_length[50]',
        'groupe' => 'permit_empty|string|max_length[50]',
        'ordre' => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'nom' => [
            'required' => 'Le nom de la permission est obligatoire.',
        ],
        'code' => [
            'required' => 'Le code de la permission est obligatoire.',
            'is_unique' => 'Ce code existe déjà.',
        ],
        'module' => [
            'required' => 'Le module est obligatoire.',
        ]
    ];

    /**
     * Get all permissions grouped by groupe
     *
     * @return array Associative array where keys are group names and values are arrays of permissions
     */
    public function getGroupedPermissions(): array
    {
        $permissions = $this->orderBy('groupe, ordre')->findAll();

        $grouped = [];
        foreach ($permissions as $permission) {
            $groupe = $permission['groupe'] ?? 'Autres';
            if (!isset($grouped[$groupe])) {
                $grouped[$groupe] = [];
            }
            $grouped[$groupe][] = $permission;
        }

        return $grouped;
    }

    /**
     * Get permissions by module
     *
     * @param string $module The module name
     * @return array Array of permissions for the specified module
     */
    public function getByModule(string $module): array
    {
        return $this->where('module', $module)->orderBy('ordre')->findAll();
    }

    /**
     * Get permission by code
     *
     * @param string $code The permission code
     * @return array|null Permission data or null if not found
     */
    public function getByCode(string $code): ?array
    {
        return $this->where('code', $code)->first();
    }

    /**
     * Get all permission codes
     *
     * @return array Array of permission codes
     */
    public function getAllCodes(): array
    {
        $permissions = $this->select('code')->findAll();
        return array_column($permissions, 'code');
    }
}
