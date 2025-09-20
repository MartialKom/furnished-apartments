<?php

namespace App\Models;

use CodeIgniter\Model;

class StructureParamModel extends Model
{
    protected $table = 'structure_params';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'param_key',
        'param_value',
        'param_type',
        'param_group',
        'description',
        'is_required'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'param_key' => 'required|max_length[100]|is_unique[structure_params.param_key,id,{id}]',
        'param_value' => 'permit_empty',
        'param_type' => 'required|in_list[string,text,number,boolean,email,phone,url]',
        'param_group' => 'required|max_length[50]',
        'description' => 'permit_empty',
        'is_required' => 'required|in_list[0,1]'
    ];

    protected $validationMessages = [
        'param_key' => [
            'required' => 'La clé du paramètre est obligatoire.',
            'max_length' => 'La clé ne peut pas dépasser 100 caractères.',
            'is_unique' => 'Cette clé de paramètre existe déjà.'
        ],
        'param_type' => [
            'required' => 'Le type du paramètre est obligatoire.',
            'in_list' => 'Type de paramètre non valide.'
        ],
        'param_group' => [
            'required' => 'Le groupe du paramètre est obligatoire.',
            'max_length' => 'Le groupe ne peut pas dépasser 50 caractères.'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Obtenir un paramètre par sa clé
     */
    public function getParam($key, $default = null)
    {
        $param = $this->where('param_key', $key)->first();
        return $param ? $param['param_value'] : $default;
    }

    /**
     * Définir un paramètre
     */
    public function setParam($key, $value, $type = 'string', $group = 'general', $description = '', $required = false)
    {
        $existing = $this->where('param_key', $key)->first();
        
        if ($existing) {
            return $this->update($existing['id'], [
                'param_value' => $value,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            return $this->insert([
                'param_key' => $key,
                'param_value' => $value,
                'param_type' => $type,
                'param_group' => $group,
                'description' => $description,
                'is_required' => $required ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    /**
     * Obtenir tous les paramètres d'un groupe
     */
    public function getParamsByGroup($group)
    {
        return $this->where('param_group', $group)->findAll();
    }

    /**
     * Obtenir tous les paramètres sous forme de tableau associatif
     */
    public function getAllParams()
    {
        $params = $this->findAll();
        $result = [];
        
        foreach ($params as $param) {
            $result[$param['param_key']] = [
                'value' => $param['param_value'],
                'type' => $param['param_type'],
                'group' => $param['param_group'],
                'description' => $param['description'],
                'required' => (bool)$param['is_required']
            ];
        }
        
        return $result;
    }

    /**
     * Obtenir les paramètres de la structure pour les contrats
     */
    public function getStructureParams()
    {
        $structureParams = [
            'structure_name' => $this->getParam('structure_name', 'APPARTEMENTS MEUBLES'),
            'structure_address' => $this->getParam('structure_address', 'Abidjan, Cocody Angré'),
            'structure_phone' => $this->getParam('structure_phone', '+225 07 07 07 07 07'),
            'structure_email' => $this->getParam('structure_email', 'contact@appartementsmeubles.com'),
            'structure_website' => $this->getParam('structure_website', ''),
            'structure_logo' => $this->getParam('structure_logo', ''),
            'structure_rc' => $this->getParam('structure_rc', ''),
            'structure_nif' => $this->getParam('structure_nif', ''),
            'structure_legal_form' => $this->getParam('structure_legal_form', 'SARL')
        ];

        return $structureParams;
    }

    /**
     * Mettre à jour plusieurs paramètres en lot
     */
    public function updateBatchParams($params)
    {
        $this->db->transStart();

        foreach ($params as $key => $value) {
            $this->setParam($key, $value);
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    /**
     * Obtenir les groupes de paramètres disponibles
     */
    public function getParamGroups()
    {
        return $this->select('param_group')
                    ->distinct()
                    ->orderBy('param_group')
                    ->findAll();
    }
}