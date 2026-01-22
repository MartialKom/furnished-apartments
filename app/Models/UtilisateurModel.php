<?php

namespace App\Models;

use CodeIgniter\Model;

class UtilisateurModel extends Model
{
    protected $table            = 'utilisateurs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nom', 'prenom', 'nomUtilisateur', 'telephone', 'email', 'role', 'role_id', 'motDePasse', 'statut'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nom' => 'required|string|max_length[100]',
        'prenom' => 'required|string|max_length[100]',
        'nomUtilisateur' => 'required|string|max_length[50]|is_unique[utilisateurs.nomUtilisateur,id,{id}]',
        'telephone' => 'required|string|max_length[20]|is_unique[utilisateurs.telephone,id,{id}]',
        'email' => 'permit_empty|valid_email|max_length[150]',
        'role' => 'permit_empty|string|max_length[50]', // Code du rôle pour compatibilité
        'role_id' => 'permit_empty|integer',
        'motDePasse' => 'required|string|min_length[6]',
        'statut' => 'permit_empty|in_list[actif,inactif]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function authentifier($identifiant, $motDePasse)
    {
        // Recherche par nomUtilisateur ou telephone
        $user = $this->groupStart()
                     ->where('nomUtilisateur', $identifiant)
                     ->orWhere('telephone', $identifiant)
                     ->groupEnd()
                     ->first();
        
        if ($user && password_verify($motDePasse, $user['motDePasse'])) {
            return $user;
        }
        
        return false;
    }

    public function gererAcces($userId)
    {
        return $this->find($userId);
    }

    protected function hashPassword(array $data)
    {
        // Pour beforeInsert et beforeUpdate, les données sont dans $data['data']
        if (isset($data['data']['motDePasse'])) {
            $data['data']['motDePasse'] = password_hash($data['data']['motDePasse'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get user with role and permissions
     *
     * @param int $userId The user ID
     * @return array|null User data with role and permissions, or null if not found
     */
    public function getUserWithPermissions(int $userId): ?array
    {
        $user = $this->find($userId);
        if (!$user) {
            return null;
        }

        // Initialize default values
        $user['role_data'] = null;
        $user['permissions'] = [];

        // Get role and permissions if user has a role assigned
        if (!empty($user['role_id'])) {
            $roleModel = new RoleModel();
            $role = $roleModel->getRoleWithPermissions($user['role_id']);

            if ($role) {
                $user['role_data'] = $role;
                $user['permissions'] = array_column($role['permissions'] ?? [], 'code');
            }
        }

        return $user;
    }

    /**
     * Get user's role
     *
     * @param int $userId The user ID
     * @return array|null Role data or null if not found
     */
    public function getUserRole(int $userId): ?array
    {
        $user = $this->find($userId);
        if (!$user || empty($user['role_id'])) {
            return null;
        }

        $roleModel = new RoleModel();
        return $roleModel->find($user['role_id']);
    }
}