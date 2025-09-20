<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StructureParamModel;

class ParametresController extends BaseController
{
    protected $structureParamModel;

    public function __construct()
    {
        $this->structureParamModel = new StructureParamModel();
    }

    /**
     * Afficher la page des paramètres
     */
    public function index()
    {
        // Vérifier que l'utilisateur est admin
        $userRole = session()->get('user_role');
        if ($userRole !== 'admin') {
            return redirect()->to('/admin/dashboard')->with('error', 'Accès non autorisé.');
        }

        $data = [
            'title' => 'Paramètres de la Structure',
            'paramGroups' => $this->structureParamModel->getParamGroups(),
            'structureParams' => $this->structureParamModel->getStructureParams()
        ];

        // Charger les paramètres par groupe
        $groups = ['structure', 'legal', 'defaults'];
        foreach ($groups as $group) {
            $data['params'][$group] = $this->structureParamModel->getParamsByGroup($group);
        }

        return view('admin/parametres/index', $data);
    }

    /**
     * Mettre à jour les paramètres
     */
    public function update()
    {
        // Vérifier que l'utilisateur est admin
        $userRole = session()->get('user_role');
        if ($userRole !== 'admin') {
            return redirect()->to('/admin/dashboard')->with('error', 'Accès non autorisé.');
        }

        $params = $this->request->getPost();

        if ($this->structureParamModel->updateBatchParams($params)) {
            return redirect()->to('/admin/parametres')->with('success', 'Paramètres mis à jour avec succès.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour des paramètres.');
        }
    }

    /**
     * Ajouter un nouveau paramètre
     */
    public function addParam()
    {
        // Vérifier que l'utilisateur est admin
        $userRole = session()->get('user_role');
        if ($userRole !== 'admin') {
            return redirect()->to('/admin/dashboard')->with('error', 'Accès non autorisé.');
        }

        $rules = [
            'param_key' => 'required|max_length[100]|alpha_dash',
            'param_value' => 'permit_empty',
            'param_type' => 'required|in_list[string,text,number,boolean,email,phone,url]',
            'param_group' => 'required|max_length[50]',
            'description' => 'permit_empty',
            'is_required' => 'permit_empty|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'param_key' => $this->request->getPost('param_key'),
            'param_value' => $this->request->getPost('param_value'),
            'param_type' => $this->request->getPost('param_type'),
            'param_group' => $this->request->getPost('param_group'),
            'description' => $this->request->getPost('description'),
            'is_required' => $this->request->getPost('is_required') ? 1 : 0
        ];

        if ($this->structureParamModel->insert($data)) {
            return redirect()->to('/admin/parametres')->with('success', 'Paramètre ajouté avec succès.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de l\'ajout du paramètre.');
        }
    }

    /**
     * Supprimer un paramètre
     */
    public function deleteParam($id)
    {
        // Vérifier que l'utilisateur est admin
        $userRole = session()->get('user_role');
        if ($userRole !== 'admin') {
            return redirect()->to('/admin/dashboard')->with('error', 'Accès non autorisé.');
        }

        if ($this->structureParamModel->delete($id)) {
            return redirect()->to('/admin/parametres')->with('success', 'Paramètre supprimé avec succès.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la suppression du paramètre.');
        }
    }

    /**
     * API pour obtenir un paramètre
     */
    public function getParam($key)
    {
        $value = $this->structureParamModel->getParam($key);
        return $this->response->setJSON(['value' => $value]);
    }
}

