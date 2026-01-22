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
            'type' => 'required|in_list[meuble,non_meuble]',
            'categorie' => 'required|in_list[appartement,logement,boutique]',
            'nombre_chambres' => 'permit_empty|integer|in_list[2,3]',
            'superficie' => 'permit_empty|decimal',
            'numero_bien' => 'permit_empty|string|max_length[50]',
            'etage' => 'permit_empty|integer',
            'description' => 'permit_empty|string',
            'equipements' => 'permit_empty|string'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Gérer l'upload des photos (stockage dans writable/uploads pour Docker)
        $uploadedPhotos = [];
        $photoFiles = $this->request->getFiles();

        // Créer le dossier s'il n'existe pas
        $uploadPath = WRITEPATH . 'uploads/appartements/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        if (isset($photoFiles['photoFiles']) && is_array($photoFiles['photoFiles'])) {
            foreach ($photoFiles['photoFiles'] as $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    // Vérifier le type de fichier
                    if (!in_array($file->getClientMimeType(), ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Format de fichier non autorisé: ' . $file->getClientName()
                        ]);
                    }

                    // Vérifier la taille (5MB max)
                    if ($file->getSizeByUnit('mb') > 5) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Fichier trop volumineux: ' . $file->getClientName() . ' (max 5MB)'
                        ]);
                    }

                    // Générer un nom unique
                    $newName = $file->getRandomName();

                    // Déplacer le fichier vers writable/uploads
                    if ($file->move($uploadPath, $newName)) {
                        $uploadedPhotos[] = 'uploads/appartements/' . $newName;
                    } else {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Erreur lors de l\'upload de: ' . $file->getClientName()
                        ]);
                    }
                }
            }
        }

        $data = [
            'adresse' => $this->request->getPost('adresse'),
            'tarifs' => $this->request->getPost('tarifs'),
            'statut' => $this->request->getPost('statut'),
            'type' => $this->request->getPost('type'),
            'categorie' => $this->request->getPost('categorie'),
            'nombre_chambres' => $this->request->getPost('nombre_chambres') ?: null,
            'superficie' => $this->request->getPost('superficie') ?: null,
            'numero_bien' => $this->request->getPost('numero_bien') ?: null,
            'etage' => $this->request->getPost('etage') ?: null,
            'description' => $this->request->getPost('description') ?: null,
            'photos' => implode(',', $uploadedPhotos),
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
            'type' => 'required|in_list[meuble,non_meuble]',
            'categorie' => 'required|in_list[appartement,logement,boutique]',
            'nombre_chambres' => 'permit_empty|integer|in_list[2,3]',
            'superficie' => 'permit_empty|decimal',
            'numero_bien' => 'permit_empty|string|max_length[50]',
            'etage' => 'permit_empty|integer',
            'description' => 'permit_empty|string',
            'equipements' => 'permit_empty|string'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Récupérer les photos existantes
        $existingPhotos = explode(',', $appartement['photos'] ?? '');
        $existingPhotos = array_filter($existingPhotos); // Supprimer les valeurs vides

        // Gérer l'upload des nouvelles photos (stockage dans writable/uploads pour Docker)
        $uploadedPhotos = [];
        $photoFiles = $this->request->getFiles();

        // Créer le dossier s'il n'existe pas
        $uploadPath = WRITEPATH . 'uploads/appartements/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        if (isset($photoFiles['photoFiles']) && is_array($photoFiles['photoFiles'])) {
            foreach ($photoFiles['photoFiles'] as $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    // Vérifications comme dans create()
                    if (!in_array($file->getClientMimeType(), ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Format de fichier non autorisé: ' . $file->getClientName()
                        ]);
                    }

                    if ($file->getSizeByUnit('mb') > 5) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Fichier trop volumineux: ' . $file->getClientName() . ' (max 5MB)'
                        ]);
                    }

                    $newName = $file->getRandomName();

                    if ($file->move($uploadPath, $newName)) {
                        $uploadedPhotos[] = 'uploads/appartements/' . $newName;
                    } else {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Erreur lors de l\'upload de: ' . $file->getClientName()
                        ]);
                    }
                }
            }
        }
        
        // Combiner photos existantes et nouvelles
        $allPhotos = array_merge($existingPhotos, $uploadedPhotos);

        $data = [
            'adresse' => $this->request->getPost('adresse'),
            'tarifs' => $this->request->getPost('tarifs'),
            'statut' => $this->request->getPost('statut'),
            'type' => $this->request->getPost('type'),
            'categorie' => $this->request->getPost('categorie'),
            'nombre_chambres' => $this->request->getPost('nombre_chambres') ?: null,
            'superficie' => $this->request->getPost('superficie') ?: null,
            'numero_bien' => $this->request->getPost('numero_bien') ?: null,
            'etage' => $this->request->getPost('etage') ?: null,
            'description' => $this->request->getPost('description') ?: null,
            'photos' => implode(',', $allPhotos),
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

    public function toggleType($id = null)
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

        // Vérifier s'il y a des réservations actives
        $reservationModel = new \App\Models\ReservationModel();
        $reservationsActives = $reservationModel->where('appartement_id', $id)
                                               ->whereIn('statut', ['confirmee', 'en_attente'])
                                               ->countAllResults();

        if ($reservationsActives > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Impossible de modifier le type d\'un appartement avec des réservations actives.'
            ]);
        }

        $nouveauType = $appartement['type'] === 'meuble' ? 'non_meuble' : 'meuble';
        
        if ($this->appartementModel->changerType($id, $nouveauType)) {
            $typeLabel = $nouveauType === 'meuble' ? 'meublé' : 'non meublé';
            return $this->response->setJSON([
                'success' => true,
                'message' => "Appartement transformé en {$typeLabel} avec succès !",
                'nouveau_type' => $nouveauType
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la modification du type.'
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