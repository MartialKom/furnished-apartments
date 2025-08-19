<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AppartementModel;
use CodeIgniter\HTTP\ResponseInterface;

class AppartementController extends BaseController
{
    protected $appartementModel;

    public function __construct()
    {
        $this->appartementModel = new AppartementModel();
    }

    public function index()
    {
        $appartements = $this->appartementModel->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'title' => 'Gestion des Appartements',
            'page_title' => 'Appartements',
            'breadcrumbs' => '<li class="breadcrumb-item">Gestion des Appartements</li><li class="breadcrumb-item active">Appartements</li>',
            'appartements' => $appartements
        ];

        return view('admin/pages/appartements/index', $data);
    }

    public function create()
    {
        $rules = [
            'adresse' => 'required|string|max_length[255]',
            'tarifs' => 'required|decimal',
            'statut' => 'required|in_list[disponible,maintenance]',
            'photos' => 'permit_empty|string',
            'equipements' => 'permit_empty|string'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'adresse' => $this->request->getPost('adresse'),
            'tarifs' => $this->request->getPost('tarifs'),
            'statut' => $this->request->getPost('statut'),
            'photos' => $this->request->getPost('photos'),
            'equipements' => $this->request->getPost('equipements')
        ];

        if ($this->appartementModel->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Appartement créé avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'appartement.'
            ]);
        }
    }

    public function update($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Appartement non trouvé.'
            ]);
        }

        $appartement = $this->appartementModel->find($id);
        if (!$appartement) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Appartement non trouvé.'
            ]);
        }

        $rules = [
            'adresse' => 'required|string|max_length[255]',
            'tarifs' => 'required|decimal',
            'statut' => 'required|in_list[disponible,maintenance]',
            'photos' => 'permit_empty|string',
            'equipements' => 'permit_empty|string'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'adresse' => $this->request->getPost('adresse'),
            'tarifs' => $this->request->getPost('tarifs'),
            'statut' => $this->request->getPost('statut'),
            'photos' => $this->request->getPost('photos'),
            'equipements' => $this->request->getPost('equipements')
        ];

        if ($this->appartementModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Appartement modifié avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la modification de l\'appartement.'
            ]);
        }
    }

    public function toggleStatus($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Appartement non trouvé.'
            ]);
        }

        $appartement = $this->appartementModel->find($id);
        if (!$appartement) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Appartement non trouvé.'
            ]);
        }

        // Ne permettre que le toggle entre disponible et maintenance
        if ($appartement['statut'] === 'occupe') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Impossible de modifier le statut d\'un appartement occupé. Annulez d\'abord les réservations confirmées.'
            ]);
        }

        $nouveauStatut = $appartement['statut'] === 'disponible' ? 'maintenance' : 'disponible';
        
        if ($this->appartementModel->update($id, ['statut' => $nouveauStatut])) {
            $action = $nouveauStatut === 'disponible' ? 'remis en service' : 'mis en maintenance';
            return $this->response->setJSON([
                'success' => true,
                'message' => "Appartement {$action} avec succès !",
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
                'message' => 'Appartement non trouvé.'
            ]);
        }

        $appartement = $this->appartementModel->find($id);
        if (!$appartement) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Appartement non trouvé.'
            ]);
        }

        // Vérifier s'il y a des réservations confirmées ou en attente
        $reservationModel = new \App\Models\ReservationModel();
        $reservationsActives = $reservationModel->where('appartement_id', $id)
                                               ->whereIn('statut', ['confirmee', 'en_attente'])
                                               ->countAllResults();

        if ($reservationsActives > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Impossible de supprimer cet appartement car il a des réservations actives.'
            ]);
        }

        if ($this->appartementModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Appartement supprimé avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'appartement.'
            ]);
        }
    }

    public function get($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Appartement non trouvé.'
            ]);
        }

        $appartement = $this->appartementModel->find($id);
        if (!$appartement) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Appartement non trouvé.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'appartement' => $appartement
        ]);
    }
}