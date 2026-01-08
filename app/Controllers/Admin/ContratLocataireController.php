<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ContratLocataireModel;
use App\Models\LocataireModel;
use App\Models\AppartementModel;
use App\Models\PaiementMensuelModel;

class ContratLocataireController extends BaseController
{
    protected $contratModel;
    protected $locataireModel;
    protected $appartementModel;
    protected $paiementModel;

    public function __construct()
    {
        $this->contratModel = new ContratLocataireModel();
        $this->locataireModel = new LocataireModel();
        $this->appartementModel = new AppartementModel();
        $this->paiementModel = new PaiementMensuelModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Contrats de Locataires',
            'contrats' => $this->contratModel->getContratsActifs()
        ];
        
        return view('admin/pages/contrats/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Nouveau Contrat',
            'locataires' => $this->locataireModel->findAll(),
            'appartements' => $this->appartementModel->where('statut', 'disponible')->findAll()
        ];
        
        return view('admin/pages/contrats/create', $data);
    }

    public function store()
    {
        $rules = [
            'locataire_id' => 'required|integer',
            'appartement_id' => 'required|integer',
            'date_debut' => 'required|valid_date',
            'date_fin' => 'permit_empty|valid_date',
            'loyer_mensuel' => 'required|decimal|greater_than[0]',
            'jour_paiement' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[31]',
            'caution' => 'permit_empty|decimal|greater_than_equal_to[0]',
            'conditions_particulieres' => 'permit_empty|string'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Vérifier la disponibilité de l'appartement
        $appartementId = $this->request->getPost('appartement_id');
        $dateDebut = $this->request->getPost('date_debut');
        $dateFin = $this->request->getPost('date_fin') ?: null;

        if (!$this->contratModel->verifierDisponibiliteAppartement($appartementId, $dateDebut, $dateFin)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'L\'appartement n\'est pas disponible pour ces dates.'
            ]);
        }

        $data = [
            'locataire_id' => $this->request->getPost('locataire_id'),
            'appartement_id' => $appartementId,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin ?: null,
            'loyer_mensuel' => $this->request->getPost('loyer_mensuel'),
            'jour_paiement' => $this->request->getPost('jour_paiement'),
            'caution' => $this->request->getPost('caution') ?: 0,
            'statut' => 'actif',
            'conditions_particulieres' => $this->request->getPost('conditions_particulieres')
        ];

        $contratId = $this->contratModel->insert($data);

        if ($contratId) {
            // Générer les échéances mensuelles pour l'année
            $this->contratModel->genererEcheancesMensuelles($contratId, 12);
            
            // Mettre à jour le statut de l'appartement
            $this->appartementModel->update($appartementId, ['statut' => 'occupe']);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Contrat créé avec succès.',
                'contrat_id' => $contratId
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la création du contrat.',
                'errors' => $this->contratModel->errors()
            ]);
        }
    }

    public function show($id)
    {
        $contrat = $this->contratModel->getContratAvecDetails($id);
        
        if (!$contrat) {
            return redirect()->to('/admin/contrats')->with('error', 'Contrat non trouvé.');
        }

        $data = [
            'title' => 'Détails du Contrat',
            'contrat' => $contrat,
            'paiements' => $this->paiementModel->getPaiementsByContrat($id),
            'resume' => $this->paiementModel->getResumeContrat($id)
        ];
        
        return view('admin/pages/contrats/show', $data);
    }

    public function edit($id)
    {
        $contrat = $this->contratModel->find($id);
        
        if (!$contrat) {
            return redirect()->to('/admin/contrats')->with('error', 'Contrat non trouvé.');
        }

        $data = [
            'title' => 'Modifier le Contrat',
            'contrat' => $contrat,
            'locataires' => $this->locataireModel->findAll(),
            'appartements' => $this->appartementModel->findAll()
        ];
        
        return view('admin/pages/contrats/edit', $data);
    }

    public function update($id)
    {
        $contrat = $this->contratModel->find($id);
        
        if (!$contrat) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Contrat non trouvé.'
            ]);
        }

        $rules = [
            'locataire_id' => 'required|integer',
            'appartement_id' => 'required|integer',
            'date_debut' => 'required|valid_date',
            'date_fin' => 'permit_empty|valid_date',
            'loyer_mensuel' => 'required|decimal|greater_than[0]',
            'jour_paiement' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[31]',
            'caution' => 'permit_empty|decimal|greater_than_equal_to[0]',
            'statut' => 'required|in_list[actif,suspendu,termine]',
            'conditions_particulieres' => 'permit_empty|string'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'locataire_id' => $this->request->getPost('locataire_id'),
            'appartement_id' => $this->request->getPost('appartement_id'),
            'date_debut' => $this->request->getPost('date_debut'),
            'date_fin' => $this->request->getPost('date_fin') ?: null,
            'loyer_mensuel' => $this->request->getPost('loyer_mensuel'),
            'jour_paiement' => $this->request->getPost('jour_paiement'),
            'caution' => $this->request->getPost('caution') ?: 0,
            'statut' => $this->request->getPost('statut'),
            'conditions_particulieres' => $this->request->getPost('conditions_particulieres')
        ];

        if ($this->contratModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Contrat mis à jour avec succès.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du contrat.',
                'errors' => $this->contratModel->errors()
            ]);
        }
    }

    public function terminer($id)
    {
        $contrat = $this->contratModel->find($id);
        
        if (!$contrat) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Contrat non trouvé.'
            ]);
        }

        $this->db->transStart();

        try {
            // Mettre à jour le statut du contrat
            $this->contratModel->update($id, ['statut' => 'termine']);
            
            // Libérer l'appartement
            $this->appartementModel->update($contrat['appartement_id'], ['statut' => 'disponible']);

            $this->db->transComplete();

            if ($this->db->transStatus()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Contrat terminé avec succès.'
                ]);
            } else {
                throw new \Exception('Erreur lors de la finalisation du contrat');
            }
        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la finalisation du contrat.'
            ]);
        }
    }

    public function genererEcheances($id)
    {
        $contrat = $this->contratModel->find($id);
        
        if (!$contrat) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Contrat non trouvé.'
            ]);
        }

        $nombreMois = $this->request->getPost('nombre_mois') ?: 12;
        
        if ($this->contratModel->genererEcheancesMensuelles($id, $nombreMois)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "Échéances générées pour $nombreMois mois."
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la génération des échéances.'
            ]);
        }
    }

    public function getEcheancesProches()
    {
        $echeances = $this->paiementModel->getEcheancesProches();
        
        return $this->response->setJSON([
            'success' => true,
            'echeances' => $echeances
        ]);
    }

    public function getRetards()
    {
        $retards = $this->paiementModel->getPaiementsEnRetard();
        
        return $this->response->setJSON([
            'success' => true,
            'retards' => $retards
        ]);
    }
}
