<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LocataireModel;
use CodeIgniter\HTTP\ResponseInterface;

class LocataireController extends BaseController
{
    protected $locataireModel;

    public function __construct()
    {
        $this->locataireModel = new LocataireModel();
    }

    public function index()
    {
        $locataires = $this->locataireModel->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'title' => 'Gestion des Locataires',
            'page_title' => 'Locataires',
            'breadcrumbs' => '<li class="breadcrumb-item">Gestion</li><li class="breadcrumb-item active">Locataires</li>',
            'locataires' => $locataires
        ];

        return view('admin/pages/locataires/index', $data);
    }

    public function create()
    {
        $rules = [
            'nom' => 'required|string|max_length[100]',
            'email' => 'required|valid_email|is_unique[locataires.email]',
            'telephone' => 'permit_empty|string|max_length[20]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'nom' => $this->request->getPost('nom'),
            'email' => $this->request->getPost('email'),
            'telephone' => $this->request->getPost('telephone')
        ];

        if ($this->locataireModel->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Locataire créé avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la création du locataire.'
            ]);
        }
    }

    public function update($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Locataire non trouvé.'
            ]);
        }

        $locataire = $this->locataireModel->find($id);
        if (!$locataire) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Locataire non trouvé.'
            ]);
        }

        $rules = [
            'nom' => 'required|string|max_length[100]',
            'email' => "required|valid_email|is_unique[locataires.email,id,{$id}]",
            'telephone' => 'permit_empty|string|max_length[20]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'nom' => $this->request->getPost('nom'),
            'email' => $this->request->getPost('email'),
            'telephone' => $this->request->getPost('telephone')
        ];

        if ($this->locataireModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Locataire modifié avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la modification du locataire.'
            ]);
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Locataire non trouvé.'
            ]);
        }

        $locataire = $this->locataireModel->find($id);
        if (!$locataire) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Locataire non trouvé.'
            ]);
        }

        // Vérifier s'il y a des réservations actives
        $reservationModel = new \App\Models\ReservationModel();
        $reservationsActives = $reservationModel->where('locataire_id', $id)
                                               ->whereIn('statut', ['confirmee', 'en_attente'])
                                               ->countAllResults();

        if ($reservationsActives > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Impossible de supprimer ce locataire car il a des réservations actives.'
            ]);
        }

        if ($this->locataireModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Locataire supprimé avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la suppression du locataire.'
            ]);
        }
    }

    public function get($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Locataire non trouvé.'
            ]);
        }

        $locataire = $this->locataireModel->find($id);
        if (!$locataire) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Locataire non trouvé.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'locataire' => $locataire
        ]);
    }
}
