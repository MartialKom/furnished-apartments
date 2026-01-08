<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ContratLocataireModel;
use App\Models\LocataireModel;
use App\Models\AppartementModel;
use App\Models\UtilisateurModel;
use App\Models\StructureParamModel;
use App\Libraries\PdfGenerator;

class ContratController extends BaseController
{
    protected $contratLocataireModel;
    protected $locataireModel;
    protected $appartementModel;
    protected $utilisateurModel;
    protected $structureParamModel;

    public function __construct()
    {
        $this->contratLocataireModel = new ContratLocataireModel();
        $this->locataireModel = new LocataireModel();
        $this->appartementModel = new AppartementModel();
        $this->utilisateurModel = new UtilisateurModel();
        $this->structureParamModel = new StructureParamModel();
    }

    /**
     * Générer un contrat de location
     */
    public function generateContrat($contratId)
    {
        // Vérifier que l'utilisateur est admin
        $userRole = session()->get('user_role'); // Corrigé: user_role au lieu de role
        if ($userRole !== 'admin') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Accès non autorisé.');
        }

        $contrat = $this->contratLocataireModel->find($contratId);
        if (!$contrat) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Contrat introuvable.');
        }

        $locataire = $this->locataireModel->find($contrat['locataire_id']);
        $appartement = $this->appartementModel->find($contrat['appartement_id']);
        $admin = $this->utilisateurModel->find(session()->get('user_id'));

        // Récupérer les paramètres de structure
        $structureParams = $this->structureParamModel->getStructureParams();

        // Données pour le contrat
        $data = [
            'contrat' => $contrat,
            'locataire' => $locataire,
            'appartement' => $appartement,
            'admin' => $admin,
            'structure_name' => $structureParams['structure_name'],
            'structure_address' => $structureParams['structure_address'],
            'structure_phone' => $structureParams['structure_phone'],
            'structure_email' => $structureParams['structure_email'],
            'structure_logo' => $structureParams['structure_logo'] ?? '',
            'structure_rc' => $structureParams['structure_rc'],
            'structure_nif' => $structureParams['structure_nif'],
            'structure_legal_form' => $structureParams['structure_legal_form'],
            'contrat_number' => 'CONTRAT-' . date('Ymd') . '-' . $contratId,
            'date_creation' => date('d/m/Y'),
            'signature_date' => date('d/m/Y'),
        ];

        return view('admin/contrats/contrat_template', $data);
    }

    /**
     * Vue pour lister les contrats avec possibilité d'impression
     */
    public function listContrats()
    {
        // Vérifier que l'utilisateur est admin
        $userRole = session()->get('user_role'); // Corrigé: user_role au lieu de role
        $userId = session()->get('user_id');
        
        // Debug: afficher les informations de session
        log_message('debug', 'ContratController::listContrats - User ID: ' . $userId . ', Role: ' . $userRole);
        
        if ($userRole !== 'admin') {
            log_message('warning', 'Accès refusé à listContrats - Role: ' . $userRole);
            return redirect()->to('/admin/dashboard')->with('error', 'Accès non autorisé. Rôle requis: admin, votre rôle: ' . $userRole);
        }

        $contrats = $this->contratLocataireModel->select('contrats_locataires.*, l.nom as locataire_nom, l.email as locataire_email, a.adresse as appartement_adresse')
            ->join('locataires l', 'l.id = contrats_locataires.locataire_id')
            ->join('appartements a', 'a.id = contrats_locataires.appartement_id')
            ->orderBy('contrats_locataires.created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Gestion des Contrats',
            'contrats' => $contrats
        ];

        return view('admin/contrats/list_contrats', $data);
    }

    /**
     * Télécharger le contrat en PDF
     */
    public function downloadContratPDF($contratId, $mode = 'D')
    {
        // Vérifier que l'utilisateur est admin
        $userRole = session()->get('user_role');
        if ($userRole !== 'admin') {
            return redirect()->to('/admin/dashboard')->with('error', 'Accès non autorisé.');
        }

        $contrat = $this->contratLocataireModel->find($contratId);
        if (!$contrat) {
            return redirect()->back()->with('error', 'Contrat introuvable.');
        }

        $locataire = $this->locataireModel->find($contrat['locataire_id']);
        $appartement = $this->appartementModel->find($contrat['appartement_id']);
        $admin = $this->utilisateurModel->find(session()->get('user_id'));

        // Récupérer les paramètres de structure
        $structureParams = $this->structureParamModel->getStructureParams();

        // Données pour le contrat
        $data = [
            'contrat' => $contrat,
            'locataire' => $locataire,
            'appartement' => $appartement,
            'admin' => $admin,
            'structure_name' => $structureParams['structure_name'],
            'structure_address' => $structureParams['structure_address'],
            'structure_phone' => $structureParams['structure_phone'],
            'structure_email' => $structureParams['structure_email'],
            'structure_logo' => $structureParams['structure_logo'] ?? '',
            'structure_rc' => $structureParams['structure_rc'],
            'structure_nif' => $structureParams['structure_nif'],
            'structure_legal_form' => $structureParams['structure_legal_form'],
            'contrat_number' => 'CONTRAT-' . date('Ymd') . '-' . $contratId,
            'date_creation' => date('d/m/Y'),
            'signature_date' => date('d/m/Y'),
        ];

        // Générer le PDF
        $pdfGenerator = new PdfGenerator();
        $filename = 'Contrat_' . $data['contrat_number'] . '.pdf';

        return $pdfGenerator->generate('admin/contrats/contrat_template', $data, $filename, $mode);
    }
}
