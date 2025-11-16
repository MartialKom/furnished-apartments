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
        $json = $this->request->getJSON(true);

        log_message('info', '=== DÉBUT CRÉATION VOITURE ===');
        log_message('info', 'Données JSON reçues: ' . json_encode($json));

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

        if (!$this->validate($rules, $json)) {
            $errors = $this->validator->getErrors();
            log_message('error', 'VALIDATION ÉCHOUÉE: ' . json_encode($errors));
            return $this->response->setJSON([
                'success' => false,
                'errors' => $errors
            ]);
        }

        $data = [
            'marque' => $json['marque'] ?? null,
            'modele' => $json['modele'] ?? null,
            'annee' => $json['annee'] ?? null,
            'immatriculation' => $json['immatriculation'] ?? null,
            'couleur' => $json['couleur'] ?? null,
            'nombre_places' => $json['nombre_places'] ?? 5,
            'type_carburant' => $json['type_carburant'] ?? 'essence',
            'transmission' => $json['transmission'] ?? 'manuelle',
            'kilometrage' => $json['kilometrage'] ?? 0,
            'tarif_journalier' => $json['tarif_journalier'] ?? null,
            'caution' => $json['caution'] ?? 0,
            'statut' => 'disponible',
            'numero_chassis' => $json['numero_chassis'] ?? null,
            'assurance_expire_le' => $json['assurance_expire_le'] ?? null,
            'visite_technique_expire_le' => $json['visite_technique_expire_le'] ?? null,
            'notes' => $json['notes'] ?? null
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

        $json = $this->request->getJSON(true);

        $rules = [
            'marque' => 'required|string|max_length[100]',
            'modele' => 'required|string|max_length[100]',
            'annee' => 'required|integer|greater_than[1900]',
            'tarif_journalier' => 'required|decimal|greater_than[0]',
            'nombre_places' => 'required|integer|greater_than[0]'
        ];

        if (!$this->validate($rules, $json)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'marque' => $json['marque'] ?? null,
            'modele' => $json['modele'] ?? null,
            'annee' => $json['annee'] ?? null,
            'couleur' => $json['couleur'] ?? null,
            'nombre_places' => $json['nombre_places'] ?? null,
            'type_carburant' => $json['type_carburant'] ?? null,
            'transmission' => $json['transmission'] ?? null,
            'kilometrage' => $json['kilometrage'] ?? null,
            'tarif_journalier' => $json['tarif_journalier'] ?? null,
            'caution' => $json['caution'] ?? null,
            'statut' => $json['statut'] ?? null,
            'assurance_expire_le' => $json['assurance_expire_le'] ?? null,
            'visite_technique_expire_le' => $json['visite_technique_expire_le'] ?? null,
            'notes' => $json['notes'] ?? null
        ];

        // Enlever les valeurs null
        $data = array_filter($data, function($value) {
            return $value !== null;
        });

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
