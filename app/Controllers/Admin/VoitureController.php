<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\VoitureModel;
use CodeIgniter\HTTP\ResponseInterface;

class VoitureController extends BaseController
{
    protected $voitureModel;

    public function __construct()
    {
        $this->voitureModel = new VoitureModel();
    }

    /**
     * Liste toutes les voitures
     */
    public function index()
    {
        $voitures = $this->voitureModel->orderBy('created_at', 'DESC')->findAll();

        // Organiser les voitures par statut
        $voituresParStatut = [
            'disponible' => [],
            'louee' => [],
            'maintenance' => [],
            'indisponible' => []
        ];

        foreach ($voitures as $voiture) {
            $voituresParStatut[$voiture['statut']][] = $voiture;
        }

        $data = [
            'title' => 'Gestion des Voitures',
            'page_title' => 'Parc Automobile',
            'breadcrumbs' => '<li class="breadcrumb-item">Administration</li><li class="breadcrumb-item active">Voitures</li>',
            'voitures' => $voituresParStatut
        ];

        return view('admin/pages/voitures/index', $data);
    }

    /**
     * Enregistrer une nouvelle voiture
     */
    public function store()
    {
        log_message('info', '=== DÉBUT CRÉATION VOITURE ===');

        $rules = [
            'marque' => 'required|string|max_length[100]',
            'modele' => 'required|string|max_length[100]',
            'annee' => 'required|integer|greater_than[1900]',
            'immatriculation' => 'required|string|max_length[50]|is_unique[voitures.immatriculation]',
            'tarif_journalier' => 'required|decimal|greater_than[0]',
            'nombre_places' => 'required|integer|greater_than[0]',
            'type_carburant' => 'required|in_list[essence,diesel,electrique,hybride]',
            'transmission' => 'required|in_list[manuelle,automatique]'
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            log_message('error', 'VALIDATION ÉCHOUÉE: ' . json_encode($errors));
            return $this->response->setJSON([
                'success' => false,
                'errors' => $errors
            ]);
        }

        // Gérer l'upload des photos
        $uploadedPhotos = [];
        $photoFiles = $this->request->getFiles();

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

                    // Déplacer le fichier
                    if ($file->move(FCPATH . 'uploads/voitures/', $newName)) {
                        $uploadedPhotos[] = 'uploads/voitures/' . $newName;
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
            'marque' => $this->request->getPost('marque'),
            'modele' => $this->request->getPost('modele'),
            'annee' => $this->request->getPost('annee'),
            'immatriculation' => $this->request->getPost('immatriculation'),
            'couleur' => $this->request->getPost('couleur'),
            'nombre_places' => $this->request->getPost('nombre_places') ?? 5,
            'type_carburant' => $this->request->getPost('type_carburant') ?? 'essence',
            'transmission' => $this->request->getPost('transmission') ?? 'manuelle',
            'kilometrage' => $this->request->getPost('kilometrage') ?? 0,
            'tarif_journalier' => $this->request->getPost('tarif_journalier'),
            'caution' => $this->request->getPost('caution') ?? 0,
            'statut' => 'disponible',
            'photos' => implode(',', $uploadedPhotos),
            'numero_chassis' => $this->request->getPost('numero_chassis'),
            'assurance_expire_le' => $this->request->getPost('assurance_expire_le'),
            'visite_technique_expire_le' => $this->request->getPost('visite_technique_expire_le'),
            'notes' => $this->request->getPost('notes')
        ];

        try {
            $voitureId = $this->voitureModel->insert($data);

            if ($voitureId) {
                log_message('info', "Voiture créée avec succès - ID: {$voitureId}");
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Voiture enregistrée avec succès !'
                ]);
            } else {
                $modelErrors = $this->voitureModel->errors();
                log_message('error', 'Échec création voiture - Erreurs: ' . json_encode($modelErrors));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de l\'enregistrement.',
                    'errors' => $modelErrors
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'EXCEPTION: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Récupérer une voiture
     */
    public function get($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Voiture non trouvée.'
            ]);
        }

        $voiture = $this->voitureModel->find($id);

        if (!$voiture) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Voiture non trouvée.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'voiture' => $voiture
        ]);
    }

    /**
     * Mettre à jour une voiture
     */
    public function update($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Voiture non trouvée.'
            ]);
        }

        $voiture = $this->voitureModel->find($id);
        if (!$voiture) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Voiture non trouvée.'
            ]);
        }

        $rules = [
            'marque' => 'required|string|max_length[100]',
            'modele' => 'required|string|max_length[100]',
            'annee' => 'required|integer|greater_than[1900]',
            'tarif_journalier' => 'required|decimal|greater_than[0]',
            'nombre_places' => 'required|integer|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Récupérer les photos existantes
        $existingPhotos = explode(',', $voiture['photos'] ?? '');
        $existingPhotos = array_filter($existingPhotos); // Supprimer les valeurs vides

        // Gérer l'upload des nouvelles photos
        $uploadedPhotos = [];
        $photoFiles = $this->request->getFiles();

        if (isset($photoFiles['photoFiles']) && is_array($photoFiles['photoFiles'])) {
            foreach ($photoFiles['photoFiles'] as $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    // Vérifications comme dans store()
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

                    if ($file->move(FCPATH . 'uploads/voitures/', $newName)) {
                        $uploadedPhotos[] = 'uploads/voitures/' . $newName;
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
            'marque' => $this->request->getPost('marque'),
            'modele' => $this->request->getPost('modele'),
            'annee' => $this->request->getPost('annee'),
            'couleur' => $this->request->getPost('couleur'),
            'nombre_places' => $this->request->getPost('nombre_places'),
            'type_carburant' => $this->request->getPost('type_carburant'),
            'transmission' => $this->request->getPost('transmission'),
            'kilometrage' => $this->request->getPost('kilometrage'),
            'tarif_journalier' => $this->request->getPost('tarif_journalier'),
            'caution' => $this->request->getPost('caution'),
            'statut' => $this->request->getPost('statut'),
            'photos' => implode(',', $allPhotos),
            'assurance_expire_le' => $this->request->getPost('assurance_expire_le'),
            'visite_technique_expire_le' => $this->request->getPost('visite_technique_expire_le'),
            'notes' => $this->request->getPost('notes')
        ];

        if ($this->voitureModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Voiture mise à jour avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour.'
            ]);
        }
    }

    /**
     * Supprimer une voiture
     */
    public function delete($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Voiture non trouvée.'
            ]);
        }

        $voiture = $this->voitureModel->find($id);
        if (!$voiture) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Voiture non trouvée.'
            ]);
        }

        // Ne pas supprimer si louée
        if ($voiture['statut'] === 'louee') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Impossible de supprimer une voiture actuellement louée.'
            ]);
        }

        if ($this->voitureModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Voiture supprimée avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la suppression.'
            ]);
        }
    }

    /**
     * Changer le statut d'une voiture
     */
    public function changerStatut($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Voiture non trouvée.'
            ]);
        }

        $json = $this->request->getJSON(true);
        $nouveauStatut = $json['statut'] ?? null;

        if (!in_array($nouveauStatut, ['disponible', 'louee', 'maintenance', 'indisponible'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Statut invalide.'
            ]);
        }

        if ($this->voitureModel->update($id, ['statut' => $nouveauStatut])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Statut mis à jour avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut.'
            ]);
        }
    }
}
