<?php

namespace App\Models;

use CodeIgniter\Model;

class RolePermissionModel extends Model
{
    protected $table            = 'role_permissions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['role_id', 'permission_id'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = null; // No updated_at field

    protected $validationRules = [
        'role_id' => 'required|integer',
        'permission_id' => 'required|integer'
    ];

    /**
     * Sync permissions for a role
     * Deletes existing permissions and inserts new ones
     *
     * @param int $roleId The role ID
     * @param array $permissionIds Array of permission IDs to assign
     * @return bool True on success
     */
    public function syncPermissions(int $roleId, array $permissionIds): bool
    {
        log_message('info', "Syncing permissions for role {$roleId}");
        log_message('info', "Permission IDs received: " . json_encode($permissionIds));

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Delete existing permissions for this role
            log_message('info', "Deleting existing permissions for role {$roleId}");
            $deleted = $this->where('role_id', $roleId)->delete();
            log_message('info', "Deleted permissions: " . ($deleted ? 'success' : 'no rows or failed'));

            // Insert new permissions
            if (!empty($permissionIds)) {
                $data = [];
                $now = date('Y-m-d H:i:s');

                foreach ($permissionIds as $permissionId) {
                    // Skip if not a valid integer
                    if (!is_numeric($permissionId)) {
                        log_message('warning', "Skipping non-numeric permission ID: {$permissionId}");
                        continue;
                    }

                    $data[] = [
                        'role_id' => $roleId,
                        'permission_id' => (int)$permissionId,
                        'created_at' => $now
                    ];
                }

                log_message('info', "Prepared " . count($data) . " permissions to insert");

                if (!empty($data)) {
                    // Use Query Builder directly to avoid timestamp issues with insertBatch
                    $builder = $db->table('role_permissions');
                    $result = $builder->insertBatch($data);
                    log_message('info', "Insert batch affected rows: " . $result);

                    if ($result === false || $result === 0) {
                        log_message('error', "Failed to insert batch");
                    }
                }
            } else {
                log_message('info', "No permissions to insert (empty array)");
            }

            $db->transComplete();
            $status = $db->transStatus();
            log_message('info', "Transaction status: " . ($status ? 'SUCCESS' : 'FAILED'));

            return $status;

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error syncing permissions for role ' . $roleId . ': ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Get permission codes for a role
     *
     * @param int $roleId The role ID
     * @return array Array of permission codes
     */
    public function getPermissionCodesForRole(int $roleId): array
    {
        $db = \Config\Database::connect();
        $result = $db->table('role_permissions rp')
            ->select('p.code')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->where('rp.role_id', $roleId)
            ->get()
            ->getResultArray();

        return array_column($result, 'code');
    }

    /**
     * Get permission IDs for a role
     *
     * @param int $roleId The role ID
     * @return array Array of permission IDs
     */
    public function getPermissionIdsForRole(int $roleId): array
    {
        $result = $this->select('permission_id')
            ->where('role_id', $roleId)
            ->findAll();

        return array_column($result, 'permission_id');
    }

    /**
     * Check if a role has a specific permission
     *
     * @param int $roleId The role ID
     * @param string $permissionCode The permission code
     * @return bool True if role has the permission, false otherwise
     */
    public function roleHasPermission(int $roleId, string $permissionCode): bool
    {
        $db = \Config\Database::connect();
        $result = $db->table('role_permissions rp')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->where('rp.role_id', $roleId)
            ->where('p.code', $permissionCode)
            ->countAllResults();

        return $result > 0;
    }

    /**
     * Get all roles that have a specific permission
     *
     * @param int $permissionId The permission ID
     * @return array Array of role IDs
     */
    public function getRolesWithPermission(int $permissionId): array
    {
        $result = $this->select('role_id')
            ->where('permission_id', $permissionId)
            ->findAll();

        return array_column($result, 'role_id');
    }
}
