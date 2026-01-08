<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\NotificationService;

class TestEmailController extends BaseController
{
    protected $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }

    /**
     * Page de test d'envoi d'email
     */
    public function index()
    {
        // Vérifier l'authentification
        if (!session()->has('user_id')) {
            return redirect()->to('/admin/login');
        }

        $data = [
            'title' => 'Test d\'envoi d\'email',
            'emailConfig' => config('Email')
        ];

        return view('admin/pages/test-email', $data);
    }

    /**
     * Envoyer un email de test via AJAX
     */
    public function sendTest()
    {
        // Vérifier l'authentification
        if (!session()->has('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Non authentifié'
            ]);
        }

        $email = $this->request->getPost('email');
        $subject = $this->request->getPost('subject') ?? 'Email de test - ' . date('Y-m-d H:i:s');
        $message = $this->request->getPost('message') ?? $this->getDefaultTestMessage();

        if (empty($email)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Veuillez fournir une adresse email'
            ]);
        }

        try {
            $result = $this->notificationService->sendEmail($email, $subject, $message);

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => "Email envoyé avec succès à {$email}. Vérifiez votre boîte de réception."
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Échec de l\'envoi de l\'email. Vérifiez la configuration SMTP et les logs.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Tester l'envoi d'un email de notification de type échéance
     */
    public function sendTestNotification()
    {
        // Vérifier l'authentification
        if (!session()->has('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Non authentifié'
            ]);
        }

        $email = $this->request->getPost('email');

        if (empty($email)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Veuillez fournir une adresse email'
            ]);
        }

        // Données de test pour la notification
        $locataire = [
            'nom' => 'Test Locataire',
            'email' => $email,
            'telephone' => '+221 77 123 45 67'
        ];

        $echeance = [
            'date_echeance' => date('Y-m-d', strtotime('+3 days')),
            'montant_du' => 500000,
            'mois_annee' => date('Y-m')
        ];

        $contrat = [
            'appartement_adresse' => 'Appartement Test - 123 Rue Example'
        ];

        try {
            // Créer un message de notification
            $subject = "Test de notification - Rappel d'échéance";
            $message = $this->generateNotificationMessage($locataire, $echeance, $contrat);

            $result = $this->notificationService->sendEmail($email, $subject, $message);

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => "Notification de test envoyée avec succès à {$email}"
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Échec de l\'envoi de la notification'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Afficher la configuration email actuelle
     */
    public function showConfig()
    {
        // Vérifier l'authentification
        if (!session()->has('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Non authentifié'
            ]);
        }

        $emailConfig = config('Email');

        return $this->response->setJSON([
            'success' => true,
            'config' => [
                'protocol' => $emailConfig->protocol,
                'SMTPHost' => $emailConfig->SMTPHost,
                'SMTPPort' => $emailConfig->SMTPPort,
                'SMTPCrypto' => $emailConfig->SMTPCrypto,
                'SMTPUser' => $emailConfig->SMTPUser,
                'SMTPPass' => !empty($emailConfig->SMTPPass) ? '***configuré***' : 'Non configuré',
                'fromEmail' => $emailConfig->fromEmail,
                'fromName' => $emailConfig->fromName,
                'isConfigured' => $this->notificationService->isEmailConfigured()
            ]
        ]);
    }

    /**
     * Message par défaut pour les tests
     */
    private function getDefaultTestMessage()
    {
        $message = "Bonjour,\n\n";
        $message .= "Ceci est un email de test envoyé depuis le système de gestion T-Lodge.\n\n";
        $message .= "Date/Heure: " . date('Y-m-d H:i:s') . "\n";
        $message .= "Système: T-Lodge Management\n\n";
        $message .= "Si vous recevez cet email, la configuration SMTP fonctionne correctement !\n\n";
        $message .= "Cordialement,\n";
        $message .= "Le système de gestion";

        return $message;
    }

    /**
     * Générer un message de notification pour les tests
     */
    private function generateNotificationMessage($locataire, $echeance, $contrat)
    {
        $joursRestants = ceil((strtotime($echeance['date_echeance']) - time()) / (60 * 60 * 24));

        $message = "Bonjour " . $locataire['nom'] . ",\n\n";
        $message .= "Ceci est un email de TEST pour le système de notification.\n\n";
        $message .= "Nous vous informons que votre loyer arrive à échéance.\n\n";
        $message .= "Détails du paiement (DONNÉES DE TEST) :\n";
        $message .= "- Montant dû : " . number_format($echeance['montant_du'], 0, ',', ' ') . " FCFA\n";
        $message .= "- Date d'échéance : " . date('d/m/Y', strtotime($echeance['date_echeance'])) . "\n";
        $message .= "- Appartement : " . $contrat['appartement_adresse'] . "\n";
        $message .= "- Jours restants : " . $joursRestants . " jour(s)\n\n";
        $message .= "NOTE: Ceci est un email de test. Aucune action n'est requise.\n\n";
        $message .= "Cordialement,\n";
        $message .= "L'équipe de gestion";

        return $message;
    }
}
