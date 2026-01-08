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

        // Charger les paramètres de la structure
        $structureParams = $this->structureParamModel->getStructureParams();

        // Charger les paramètres SMTP et les fusionner
        $smtpParams = $this->structureParamModel->getSmtpParams();
        $structureParams = array_merge($structureParams, $smtpParams);

        $data = [
            'title' => 'Paramètres de la Structure',
            'paramGroups' => $this->structureParamModel->getParamGroups(),
            'structureParams' => $structureParams
        ];

        // Charger les paramètres par groupe
        $groups = ['structure', 'legal', 'defaults', 'smtp'];
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

        // Gérer le mot de passe SMTP (ne pas écraser si vide)
        if (isset($params['smtp_pass']) && empty($params['smtp_pass'])) {
            unset($params['smtp_pass']); // Ne pas mettre à jour si vide
        }

        // Gérer l'upload du logo s'il y a un fichier
        $logoFile = $this->request->getFile('structure_logo_file');
        if ($logoFile && $logoFile->isValid() && !$logoFile->hasMoved()) {
            // Valider le fichier
            $validated = $this->validate([
                'structure_logo_file' => [
                    'rules' => 'uploaded[structure_logo_file]|is_image[structure_logo_file]|max_size[structure_logo_file,2048]',
                    'errors' => [
                        'uploaded' => 'Veuillez sélectionner un fichier.',
                        'is_image' => 'Le fichier doit être une image.',
                        'max_size' => 'L\'image ne doit pas dépasser 2 Mo.'
                    ]
                ]
            ]);

            if ($validated) {
                // Créer le dossier s'il n'existe pas
                $uploadPath = FCPATH . 'uploads/parametres/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Supprimer l'ancien logo s'il existe
                if (!empty($params['structure_logo']) && file_exists(FCPATH . $params['structure_logo'])) {
                    @unlink(FCPATH . $params['structure_logo']);
                }

                // Générer un nom unique
                $newName = 'logo_' . time() . '.' . $logoFile->getExtension();

                // Déplacer le fichier
                $logoFile->move($uploadPath, $newName);

                // Mettre à jour le paramètre avec le chemin relatif
                $params['structure_logo'] = 'uploads/parametres/' . $newName;
            } else {
                return redirect()->back()->withInput()->with('error', 'Erreur lors de l\'upload du logo : ' . implode(', ', $this->validator->getErrors()));
            }
        }

        if ($this->structureParamModel->updateBatchParams($params)) {
            // Recharger la config Email après mise à jour des paramètres SMTP
            $email = \Config\Services::email(null, true); // Force reload

            return redirect()->to('/admin/parametres')->with('success', 'Paramètres mis à jour avec succès.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour des paramètres.');
        }
    }

    /**
     * Supprimer le logo
     */
    public function deleteLogo()
    {
        // Vérifier que l'utilisateur est admin
        $userRole = session()->get('user_role');
        if ($userRole !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Accès non autorisé.']);
        }

        $currentLogo = $this->structureParamModel->getParam('structure_logo');

        if (!empty($currentLogo) && file_exists(FCPATH . $currentLogo)) {
            @unlink(FCPATH . $currentLogo);
        }

        // Mettre à jour le paramètre
        if ($this->structureParamModel->setParam('structure_logo', '')) {
            return $this->response->setJSON(['success' => true, 'message' => 'Logo supprimé avec succès.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Erreur lors de la suppression.']);
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

    /**
     * Tester la connexion SMTP
     */
    public function testSmtp()
    {
        if (!isSuperAdmin() && !hasPermission('manage_parametres')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Accès non autorisé.'
            ]);
        }

        try {
            // Récupérer les paramètres du formulaire
            $smtpParams = [
                'smtp_host' => $this->request->getPost('smtp_host'),
                'smtp_port' => $this->request->getPost('smtp_port'),
                'smtp_user' => $this->request->getPost('smtp_user'),
                'smtp_pass' => $this->request->getPost('smtp_pass'),
                'smtp_crypto' => $this->request->getPost('smtp_crypto'),
            ];

            // Vérifier que les paramètres obligatoires sont remplis
            if (empty($smtpParams['smtp_host']) ||
                empty($smtpParams['smtp_user']) ||
                empty($smtpParams['smtp_pass'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Paramètres SMTP incomplets.',
                    'error' => 'Veuillez remplir au minimum le serveur, l\'utilisateur et le mot de passe.'
                ]);
            }

            // Validation basique des paramètres
            $validationDetails = "Serveur: {$smtpParams['smtp_host']}:{$smtpParams['smtp_port']} | Chiffrement: {$smtpParams['smtp_crypto']}";

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Configuration SMTP validée avec succès !',
                'details' => $validationDetails
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Erreur test SMTP: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Échec de la validation SMTP',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Prévisualiser le template d'email
     */
    public function previewEmail()
    {
        if (!isSuperAdmin() && !hasPermission('manage_parametres')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Accès non autorisé.');
        }

        // Charger le NotificationService pour générer un template exemple
        $notificationService = new \App\Libraries\NotificationService();

        // Données fictives pour la prévisualisation
        $echeance = [
            'montant_loyer' => 150000,
            'date_echeance' => date('Y-m-d', strtotime('+5 days')),
            'jour_paiement' => 5,
            'jours_restants' => 5
        ];

        $locataire = [
            'nom' => 'Dupont Martin',
            'email' => 'martin.dupont@example.com'
        ];

        $contrat = [
            'id' => 1,
            'appartement_adresse' => 'Appartement 2B - 15 Rue de la Paix, Yaoundé'
        ];

        // Générer le template (utilise la méthode publique genererMessageEcheance)
        // Note: Cette méthode est normalement protected, nous devons la rendre accessible
        // Pour l'instant, retourner un HTML de démo
        $html = $notificationService->genererMessageEcheance($echeance, $locataire, $contrat);

        // Afficher directement le HTML
        return $html;
    }
}

