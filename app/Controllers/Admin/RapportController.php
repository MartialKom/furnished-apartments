<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RapportModel;

class RapportController extends BaseController
{
    protected $rapportModel;

    public function __construct()
    {
        $this->rapportModel = new RapportModel();
    }

    /**
     * Liste tous les rapports
     */
    public function index()
    {
        $rapports = $this->rapportModel->getRapportsWithUser();

        $data = [
            'title' => 'Rapports',
            'page_title' => 'Rapports',
            'breadcrumbs' => '<li class="breadcrumb-item">Rapports & Analyse</li><li class="breadcrumb-item active">Rapports</li>',
            'rapports' => $rapports
        ];

        return view('admin/pages/rapports/index', $data);
    }

    /**
     * Créer un nouveau rapport
     */
    public function create()
    {
        $rules = [
            'date_rapport' => 'required|valid_date',
            'contenu' => 'required|string|min_length[10]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $session = session();
        $data = [
            'utilisateur_id' => $session->get('user_id'),
            'date_rapport' => $this->request->getPost('date_rapport'),
            'contenu' => $this->request->getPost('contenu')
        ];

        if ($this->rapportModel->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Rapport créé avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la création du rapport.',
                'errors' => $this->rapportModel->errors()
            ]);
        }
    }

    /**
     * Récupérer un rapport spécifique
     */
    public function get($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Rapport non trouvé.'
            ]);
        }

        $rapport = $this->rapportModel->getRapportWithUser($id);
        if (!$rapport) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Rapport non trouvé.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $rapport
        ]);
    }

    /**
     * Mettre à jour un rapport
     */
    public function update($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Rapport non trouvé.'
            ]);
        }

        $rapport = $this->rapportModel->find($id);
        if (!$rapport) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Rapport non trouvé.'
            ]);
        }

        // Vérifier que l'utilisateur est propriétaire du rapport ou admin
        $session = session();
        if ($rapport['utilisateur_id'] != $session->get('user_id') && $session->get('user_role') != 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à modifier ce rapport.'
            ]);
        }

        $rules = [
            'date_rapport' => 'required|valid_date',
            'contenu' => 'required|string|min_length[10]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'date_rapport' => $this->request->getPost('date_rapport'),
            'contenu' => $this->request->getPost('contenu')
        ];

        if ($this->rapportModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Rapport modifié avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la modification du rapport.'
            ]);
        }
    }

    /**
     * Supprimer un rapport
     */
    public function delete($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Rapport non trouvé.'
            ]);
        }

        $rapport = $this->rapportModel->find($id);
        if (!$rapport) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Rapport non trouvé.'
            ]);
        }

        // Vérifier que l'utilisateur est propriétaire du rapport ou admin
        $session = session();
        if ($rapport['utilisateur_id'] != $session->get('user_id') && $session->get('user_role') != 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à supprimer ce rapport.'
            ]);
        }

        if ($this->rapportModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Rapport supprimé avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la suppression du rapport.'
            ]);
        }
    }
}
