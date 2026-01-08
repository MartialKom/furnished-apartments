<?php

namespace App\Libraries;

use CodeIgniter\Email\Email;

class NotificationService
{
    protected $email;
    protected $config;

    protected $structureParams;
    protected $logoBase64;
    protected $structureParamModel;

    public function __construct()
    {
        $this->email = \Config\Services::email();
        $this->config = config('Email');

        // Charger les paramètres de la structure
        $this->structureParamModel = new \App\Models\StructureParamModel();
        $this->structureParams = $this->structureParamModel->getStructureParams();

        // Charger le logo encodé en Base64
        $this->logoBase64 = $this->structureParamModel->getLogoBase64();
    }
    
    /**
     * Vérifie si l'email est correctement configuré
     */
    public function isEmailConfigured()
    {
        // Vérifier paramètres depuis BD d'abord
        $smtpParams = $this->structureParamModel->getSmtpParams();

        if (!empty($smtpParams['smtp_host']) &&
            !empty($smtpParams['smtp_user']) &&
            !empty($smtpParams['smtp_pass'])) {
            return true;
        }

        // Fallback: vérifier config chargée (qui inclut déjà le fallback .env)
        if (!empty($this->config->SMTPHost) &&
            !empty($this->config->SMTPUser) &&
            !empty($this->config->SMTPPass)) {
            return true;
        }

        log_message('warning', 'Configuration SMTP non trouvée ni en BD ni dans .env');
        return false;
    }

    /**
     * Envoyer un email simple
     */
    public function sendEmail($to, $subject, $message, $isHtml = false)
    {
        // Vérifier si SMTP est configuré
        if (!$this->isEmailConfigured()) {
            log_message('warning', "SMTP non configuré - Email simulé pour {$to}: {$subject}");
            $this->logNotification($to, $subject, $message, 'info');
            return false;
        }

        try {
            $this->email->clear();
            $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
            $this->email->setTo($to);
            $this->email->setSubject($subject);

            if ($isHtml) {
                $this->email->setMailType('html');
            }

            $this->email->setMessage($message);

            $result = $this->email->send();

            if ($result) {
                log_message('info', "Email envoyé avec succès à {$to}");
                $this->logNotification($to, $subject, $message, 'info');
            } else {
                $error = $this->email->printDebugger(['headers']);
                log_message('error', "Échec envoi email à {$to}: {$error}");
                $this->logNotification($to, $subject, $message . "\nErreur: " . $error, 'error');
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', "Exception lors de l'envoi d'email: " . $e->getMessage());
            $this->logNotification($to, $subject, $message . "\nException: " . $e->getMessage(), 'error');
            return false;
        }
    }

    /**
     * Enregistre une notification dans les logs
     */
    protected function logNotification($to, $subject, $message, $type = 'info')
    {
        $logData = [
            'date' => date('Y-m-d H:i:s'),
            'to' => $to,
            'subject' => $subject,
            'message' => substr($message, 0, 500), // Limiter la taille
            'type' => $type
        ];
        
        log_message($type, "NOTIFICATION: " . json_encode($logData));
        
        // Sauvegarder en base de données si possible
        $this->saveNotificationToDatabase($logData);
    }
    
    /**
     * Sauvegarde la notification en base de données
     */
    protected function saveNotificationToDatabase($logData)
    {
        try {
            $db = \Config\Database::connect();
            
            // Créer la table si elle n'existe pas
            $this->createNotificationsTableIfNotExists($db);
            
            $db->table('notifications_log')->insert([
                'date' => $logData['date'],
                'recipient_email' => $logData['to'],
                'subject' => $logData['subject'],
                'message' => $logData['message'],
                'status' => $logData['type'] === 'info' ? 'logged' : 'failed',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            log_message('error', "Erreur lors de la sauvegarde de la notification: " . $e->getMessage());
        }
    }
    
    /**
     * Crée la table de log des notifications si elle n'existe pas
     */
    protected function createNotificationsTableIfNotExists($db)
    {
        $sql = "CREATE TABLE IF NOT EXISTS notifications_log (
            id INT AUTO_INCREMENT PRIMARY KEY,
            date DATETIME NOT NULL,
            recipient_email VARCHAR(255) NOT NULL,
            subject VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            status ENUM('sent', 'failed', 'logged') DEFAULT 'logged',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $db->query($sql);
    }

    /**
     * Envoyer une notification d'échéance proche
     */
    public function envoyerNotificationEcheance($locataire, $echeance, $contrat)
    {
        $sujet = "Rappel de paiement - Échéance du " . date('d/m/Y', strtotime($echeance['date_echeance']));
        $message = $this->genererMessageEcheance($locataire, $echeance, $contrat);

        // Vérifier si SMTP est configuré
        if (!$this->isEmailConfigured()) {
            log_message('warning', "SMTP non configuré - Notification simulée pour {$locataire['email']}: {$sujet}");
            $this->logNotification($locataire['email'], $sujet, strip_tags($message), 'info');

            // Simuler l'envoi aux administrateurs
            $this->envoyerNotificationAdmin($locataire, $echeance, $contrat);
            return true; // Retourner true pour ne pas bloquer le processus
        }

        try {
            $this->email->clear();
            $this->email->setFrom($this->config->fromEmail, $this->structureParams['structure_name']);
            $this->email->setTo($locataire['email']);
            $this->email->setSubject($sujet);
            $this->email->setMailType('html');
            $this->email->setMessage($message);

            $result = $this->email->send();

            if ($result) {
                log_message('info', "Notification échéance envoyée avec succès à {$locataire['email']}");
                $this->logNotification($locataire['email'], $sujet, strip_tags($message), 'info');
                // Envoyer aussi aux administrateurs
                $this->envoyerNotificationAdmin($locataire, $echeance, $contrat);
            } else {
                $error = $this->email->printDebugger(['headers']);
                log_message('error', "Échec envoi notification échéance à {$locataire['email']}: {$error}");
                $this->logNotification($locataire['email'], $sujet, strip_tags($message), 'error');
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', "Exception lors de l'envoi de notification échéance: " . $e->getMessage());
            $this->logNotification($locataire['email'], $sujet, strip_tags($message), 'error');
            return false;
        }
    }

    /**
     * Envoyer une notification de retard de paiement
     */
    public function envoyerNotificationRetard($locataire, $echeance, $contrat)
    {
        $sujet = "URGENT: Retard de paiement - Action requise";
        $message = $this->genererMessageRetard($locataire, $echeance, $contrat);

        // Vérifier si SMTP est configuré
        if (!$this->isEmailConfigured()) {
            log_message('warning', "SMTP non configuré - Notification retard simulée pour {$locataire['email']}: {$sujet}");
            $this->logNotification($locataire['email'], $sujet, strip_tags($message), 'warning');

            // Simuler l'envoi aux administrateurs
            $this->envoyerNotificationAdminRetard($locataire, $echeance, $contrat);
            return true; // Retourner true pour ne pas bloquer le processus
        }

        try {
            $this->email->clear();
            $this->email->setFrom($this->config->fromEmail, $this->structureParams['structure_name']);
            $this->email->setTo($locataire['email']);
            $this->email->setSubject($sujet);
            $this->email->setMailType('html');
            $this->email->setMessage($message);

            $result = $this->email->send();

            if ($result) {
                log_message('info', "Notification retard envoyée avec succès à {$locataire['email']}");
                $this->logNotification($locataire['email'], $sujet, strip_tags($message), 'info');
                // Envoyer aux administrateurs
                $this->envoyerNotificationAdminRetard($locataire, $echeance, $contrat);
            } else {
                $error = $this->email->printDebugger(['headers']);
                log_message('error', "Échec envoi notification retard à {$locataire['email']}: {$error}");
                $this->logNotification($locataire['email'], $sujet, strip_tags($message), 'error');
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', "Exception lors de l'envoi de notification retard: " . $e->getMessage());
            $this->logNotification($locataire['email'], $sujet, strip_tags($message), 'error');
            return false;
        }
    }

    /**
     * Envoyer une notification aux administrateurs
     */
    private function envoyerNotificationAdmin($locataire, $echeance, $contrat)
    {
        $utilisateurModel = new \App\Models\UtilisateurModel();
        $admins = $utilisateurModel->where('notifications_email', true)->findAll();
        
        $sujet = "Notification Admin - Échéance proche pour " . $locataire['nom'];
        $message = $this->genererMessageAdminEcheance($locataire, $echeance, $contrat);
        
        foreach ($admins as $admin) {
            $this->email->clear();
            $this->email->setFrom($this->config->fromEmail, 'Système de Gestion - Appartements Meublés');
            $this->email->setTo($admin['email']);
            $this->email->setSubject($sujet);
            $this->email->setMessage($message);
            $this->email->send();
        }
    }

    /**
     * Envoyer une notification de retard aux administrateurs
     */
    private function envoyerNotificationAdminRetard($locataire, $echeance, $contrat)
    {
        $utilisateurModel = new \App\Models\UtilisateurModel();
        $admins = $utilisateurModel->where('notifications_email', true)->findAll();
        
        $sujet = "URGENT - Retard de paiement pour " . $locataire['nom'];
        $message = $this->genererMessageAdminRetard($locataire, $echeance, $contrat);
        
        foreach ($admins as $admin) {
            $this->email->clear();
            $this->email->setFrom($this->config->fromEmail, 'Système de Gestion - Appartements Meublés');
            $this->email->setTo($admin['email']);
            $this->email->setSubject($sujet);
            $this->email->setMessage($message);
            $this->email->send();
        }
    }

    /**
     * Générer le message d'échéance pour le locataire
     */
    public function genererMessageEcheance($locataire, $echeance, $contrat)
    {
        $joursRestants = (strtotime($echeance['date_echeance']) - time()) / (60 * 60 * 24);
        $joursRestants = ceil($joursRestants);

        $html = $this->getEmailHeader();

        $html .= '<div style="background-color: #FFA726; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;">';
        $html .= '<h1 style="margin: 0; font-size: 28px;">Rappel de Paiement</h1>';
        $html .= '</div>';

        $html .= '<div style="padding: 30px;">';
        $html .= '<p style="font-size: 16px; color: #333;">Bonjour <strong>' . esc($locataire['nom']) . '</strong>,</p>';
        $html .= '<p style="color: #666;">Nous vous informons que votre loyer pour le mois de <strong>' . $this->formaterMoisAnnee($echeance['mois_annee']) . '</strong> arrive à échéance.</p>';

        $html .= '<div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; margin: 20px 0;">';
        $html .= '<h3 style="color: #856404; margin-top: 0;">⏰ Échéance dans ' . $joursRestants . ' jour(s)</h3>';
        $html .= '</div>';

        $html .= '<div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;">';
        $html .= '<h3 style="color: #333; margin-top: 0;">Détails du paiement</h3>';
        $html .= '<table style="width: 100%; border-collapse: collapse;">';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Appartement:</td><td style="padding: 10px 0; font-weight: bold; color: #333;">' . esc($contrat['appartement_adresse']) . '</td></tr>';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Période:</td><td style="padding: 10px 0; font-weight: bold; color: #333;">' . $this->formaterMoisAnnee($echeance['mois_annee']) . '</td></tr>';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Montant dû:</td><td style="padding: 10px 0; font-weight: bold; color: #4CAF50; font-size: 20px;">' . number_format($echeance['montant_du'], 0, ',', ' ') . ' FCFA</td></tr>';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Date d\'échéance:</td><td style="padding: 10px 0; font-weight: bold; color: #FF9800;">' . date('d/m/Y', strtotime($echeance['date_echeance'])) . '</td></tr>';
        $html .= '</table>';
        $html .= '</div>';

        $html .= '<div style="background-color: #e3f2fd; border-left: 4px solid #2196F3; padding: 15px; margin: 20px 0;">';
        $html .= '<p style="margin: 0; color: #1976D2;"><strong>Important:</strong></p>';
        $html .= '<p style="margin: 10px 0 0 0; color: #1976D2;">Veuillez effectuer votre paiement avant la date d\'échéance pour éviter les frais de retard.</p>';
        $html .= '</div>';

        $html .= '<p style="color: #666; margin-top: 30px;">Si vous avez déjà effectué le paiement, veuillez nous en informer.</p>';
        $html .= '<p style="color: #666;">Pour toute question, n\'hésitez pas à nous contacter.</p>';
        $html .= '</div>';

        $html .= $this->getEmailFooter();

        return $html;
    }

    /**
     * Générer le message de retard pour le locataire
     */
    private function genererMessageRetard($locataire, $echeance, $contrat)
    {
        $joursRetard = (time() - strtotime($echeance['date_echeance'])) / (60 * 60 * 24);
        $joursRetard = floor($joursRetard);

        $html = $this->getEmailHeader();

        $html .= '<div style="background-color: #f44336; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;">';
        $html .= '<h1 style="margin: 0; font-size: 28px;">⚠ URGENT - Retard de Paiement</h1>';
        $html .= '</div>';

        $html .= '<div style="padding: 30px;">';
        $html .= '<p style="font-size: 16px; color: #333;">Bonjour <strong>' . esc($locataire['nom']) . '</strong>,</p>';
        $html .= '<p style="color: #666;">Nous constatons que votre loyer pour le mois de <strong>' . $this->formaterMoisAnnee($echeance['mois_annee']) . '</strong> n\'a pas encore été réglé.</p>';

        $html .= '<div style="background-color: #ffebee; border-left: 4px solid #f44336; padding: 20px; margin: 20px 0;">';
        $html .= '<h3 style="color: #c62828; margin-top: 0;">⏰ Retard: ' . $joursRetard . ' jour(s)</h3>';
        $html .= '<p style="margin: 10px 0 0 0; color: #c62828;">Votre paiement aurait dû être effectué le ' . date('d/m/Y', strtotime($echeance['date_echeance'])) . '</p>';
        $html .= '</div>';

        $html .= '<div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;">';
        $html .= '<h3 style="color: #333; margin-top: 0;">Détails du paiement en retard</h3>';
        $html .= '<table style="width: 100%; border-collapse: collapse;">';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Appartement:</td><td style="padding: 10px 0; font-weight: bold; color: #333;">' . esc($contrat['appartement_adresse']) . '</td></tr>';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Période:</td><td style="padding: 10px 0; font-weight: bold; color: #333;">' . $this->formaterMoisAnnee($echeance['mois_annee']) . '</td></tr>';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Montant dû:</td><td style="padding: 10px 0; font-weight: bold; color: #f44336; font-size: 20px;">' . number_format($echeance['montant_du'], 0, ',', ' ') . ' FCFA</td></tr>';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Date d\'échéance:</td><td style="padding: 10px 0; font-weight: bold; color: #FF5722;">' . date('d/m/Y', strtotime($echeance['date_echeance'])) . '</td></tr>';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Jours de retard:</td><td style="padding: 10px 0; font-weight: bold; color: #f44336; font-size: 18px;">' . $joursRetard . ' jour(s)</td></tr>';
        $html .= '</table>';
        $html .= '</div>';

        $html .= '<div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;">';
        $html .= '<p style="margin: 0; color: #856404;"><strong>Action requise:</strong></p>';
        $html .= '<p style="margin: 10px 0 0 0; color: #856404;">Veuillez régulariser votre situation dans les plus brefs délais pour éviter toute procédure supplémentaire.</p>';
        $html .= '</div>';

        $html .= '<div style="background-color: #e3f2fd; border-left: 4px solid #2196F3; padding: 15px; margin: 20px 0;">';
        $html .= '<p style="margin: 0; color: #1976D2;"><strong>En cas de difficultés:</strong></p>';
        $html .= '<p style="margin: 10px 0 0 0; color: #1976D2;">Si vous rencontrez des difficultés pour effectuer le paiement, nous vous invitons à nous contacter rapidement pour trouver une solution ensemble.</p>';
        $html .= '</div>';

        $html .= '<p style="color: #666; margin-top: 30px;">Si vous avez déjà effectué le paiement, veuillez nous en informer immédiatement.</p>';
        $html .= '<p style="color: #666;">Nous restons à votre disposition pour toute question.</p>';
        $html .= '</div>';

        $html .= $this->getEmailFooter();

        return $html;
    }

    /**
     * Générer le message admin pour échéance proche
     */
    private function genererMessageAdminEcheance($locataire, $echeance, $contrat)
    {
        $message = "Notification Admin - Échéance proche\n\n";
        $message .= "Locataire : " . $locataire['nom'] . "\n";
        $message .= "Email : " . $locataire['email'] . "\n";
        $message .= "Téléphone : " . $locataire['telephone'] . "\n";
        $message .= "Appartement : " . $contrat['appartement_adresse'] . "\n";
        $message .= "Montant : " . number_format($echeance['montant_du'], 0, ',', ' ') . " FCFA\n";
        $message .= "Échéance : " . date('d/m/Y', strtotime($echeance['date_echeance'])) . "\n\n";
        $message .= "Action recommandée : Vérifier le paiement dans les prochains jours.";
        
        return $message;
    }

    /**
     * Générer le message admin pour retard
     */
    private function genererMessageAdminRetard($locataire, $echeance, $contrat)
    {
        $joursRetard = (time() - strtotime($echeance['date_echeance'])) / (60 * 60 * 24);
        $joursRetard = floor($joursRetard);
        
        $message = "URGENT - Retard de paiement\n\n";
        $message .= "Locataire : " . $locataire['nom'] . "\n";
        $message .= "Email : " . $locataire['email'] . "\n";
        $message .= "Téléphone : " . $locataire['telephone'] . "\n";
        $message .= "Appartement : " . $contrat['appartement_adresse'] . "\n";
        $message .= "Montant : " . number_format($echeance['montant_du'], 0, ',', ' ') . " FCFA\n";
        $message .= "Échéance : " . date('d/m/Y', strtotime($echeance['date_echeance'])) . "\n";
        $message .= "Jours de retard : " . $joursRetard . "\n\n";
        $message .= "Action requise : Contacter le locataire immédiatement.";
        
        return $message;
    }

    /**
     * Formater le mois/année pour l'affichage
     */
    private function formaterMoisAnnee($moisAnnee)
    {
        $mois = [
            '01' => 'Janvier', '02' => 'Février', '03' => 'Mars', '04' => 'Avril',
            '05' => 'Mai', '06' => 'Juin', '07' => 'Juillet', '08' => 'Août',
            '09' => 'Septembre', '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre'
        ];

        list($annee, $moisNum) = explode('-', $moisAnnee);
        return $mois[$moisNum] . ' ' . $annee;
    }

    /**
     * Envoyer un email de confirmation de réservation au client
     */
    public function envoyerConfirmationReservation($reservation, $appartement)
    {
        $sujet = "Confirmation de votre réservation - " . $this->structureParams['structure_name'];
        $message = $this->genererTemplateConfirmationReservation($reservation, $appartement);

        if (!$this->isEmailConfigured()) {
            log_message('warning', "SMTP non configuré - Email de confirmation simulé pour {$reservation['client_email']}");
            $this->logNotification($reservation['client_email'], $sujet, strip_tags($message), 'info');
            return false;
        }

        try {
            $this->email->clear();
            $this->email->setFrom($this->config->fromEmail, $this->structureParams['structure_name']);
            $this->email->setTo($reservation['client_email']);
            $this->email->setSubject($sujet);
            $this->email->setMailType('html');
            $this->email->setMessage($message);

            $result = $this->email->send();

            if ($result) {
                log_message('info', "Email de confirmation envoyé à {$reservation['client_email']}");
                $this->logNotification($reservation['client_email'], $sujet, strip_tags($message), 'info');
            } else {
                $error = $this->email->printDebugger(['headers']);
                log_message('error', "Échec envoi confirmation à {$reservation['client_email']}: {$error}");
                $this->logNotification($reservation['client_email'], $sujet, strip_tags($message), 'error');
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', "Exception envoi confirmation: " . $e->getMessage());
            $this->logNotification($reservation['client_email'], $sujet, strip_tags($message), 'error');
            return false;
        }
    }

    /**
     * Envoyer une notification de nouvelle réservation au gestionnaire
     */
    public function envoyerNotificationNouvelleReservation($reservation, $appartement)
    {
        $emailGestionnaire = getenv('SMTP_USER_GESTIONNAIRE') ?: 'gestionnaire@nsenoutower.com';
        $sujet = "Nouvelle réservation en ligne - " . $reservation['client_nom'];
        $message = $this->genererTemplateNouvelleReservation($reservation, $appartement);

        if (!$this->isEmailConfigured()) {
            log_message('warning', "SMTP non configuré - Notification gestionnaire simulée");
            $this->logNotification($emailGestionnaire, $sujet, strip_tags($message), 'info');
            return false;
        }

        try {
            $this->email->clear();
            $this->email->setFrom($this->config->fromEmail, $this->structureParams['structure_name']);
            $this->email->setTo($emailGestionnaire);
            $this->email->setSubject($sujet);
            $this->email->setMailType('html');
            $this->email->setMessage($message);

            $result = $this->email->send();

            if ($result) {
                log_message('info', "Notification gestionnaire envoyée pour réservation #{$reservation['id']}");
            } else {
                $error = $this->email->printDebugger(['headers']);
                log_message('error', "Échec notification gestionnaire: {$error}");
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', "Exception notification gestionnaire: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Générer le template HTML pour la confirmation de réservation
     */
    private function genererTemplateConfirmationReservation($reservation, $appartement)
    {
        $html = $this->getEmailHeader();

        $html .= '<div style="background-color: #4CAF50; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;">';
        $html .= '<h1 style="margin: 0; font-size: 28px;">Réservation Confirmée</h1>';
        $html .= '</div>';

        $html .= '<div style="padding: 30px;">';
        $html .= '<p style="font-size: 16px; color: #333;">Bonjour <strong>' . esc($reservation['client_nom']) . '</strong>,</p>';
        $html .= '<p style="color: #666;">Nous avons bien reçu votre demande de réservation et nous vous remercions de votre confiance.</p>';

        $html .= '<div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;">';
        $html .= '<h3 style="color: #333; margin-top: 0;">Détails de votre réservation</h3>';
        $html .= '<table style="width: 100%; border-collapse: collapse;">';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Appartement:</td><td style="padding: 10px 0; font-weight: bold; color: #333;">' . esc($appartement['adresse']) . '</td></tr>';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Date d\'arrivée:</td><td style="padding: 10px 0; font-weight: bold; color: #333;">' . date('d/m/Y', strtotime($reservation['date_debut'])) . '</td></tr>';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Date de départ:</td><td style="padding: 10px 0; font-weight: bold; color: #333;">' . date('d/m/Y', strtotime($reservation['date_fin'])) . '</td></tr>';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Montant total:</td><td style="padding: 10px 0; font-weight: bold; color: #4CAF50; font-size: 18px;">' . number_format($reservation['montant_total'], 0, ',', ' ') . ' FCFA</td></tr>';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Statut:</td><td style="padding: 10px 0;"><span style="background-color: #FFA726; color: white; padding: 5px 15px; border-radius: 20px; font-size: 12px;">EN ATTENTE</span></td></tr>';
        $html .= '</table>';
        $html .= '</div>';

        $html .= '<div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;">';
        $html .= '<p style="margin: 0; color: #856404;"><strong>Prochaines étapes:</strong></p>';
        $html .= '<p style="margin: 10px 0 0 0; color: #856404;">Notre équipe va examiner votre demande et vous contactera dans les plus brefs délais pour confirmer la réservation et les modalités de paiement.</p>';
        $html .= '</div>';

        $html .= '<p style="color: #666; margin-top: 30px;">Si vous avez des questions, n\'hésitez pas à nous contacter.</p>';
        $html .= '</div>';

        $html .= $this->getEmailFooter();

        return $html;
    }

    /**
     * Générer le template HTML pour la notification au gestionnaire
     */
    private function genererTemplateNouvelleReservation($reservation, $appartement)
    {
        $html = $this->getEmailHeader();

        $html .= '<div style="background-color: #2196F3; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;">';
        $html .= '<h1 style="margin: 0; font-size: 28px;">Nouvelle Réservation</h1>';
        $html .= '</div>';

        $html .= '<div style="padding: 30px;">';
        $html .= '<p style="font-size: 16px; color: #333;">Une nouvelle réservation a été effectuée via le site web.</p>';

        $html .= '<div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;">';
        $html .= '<h3 style="color: #333; margin-top: 0;">Informations du client</h3>';
        $html .= '<table style="width: 100%; border-collapse: collapse;">';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Nom:</td><td style="padding: 10px 0; font-weight: bold; color: #333;">' . esc($reservation['client_nom']) . '</td></tr>';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Email:</td><td style="padding: 10px 0; font-weight: bold; color: #333;">' . esc($reservation['client_email']) . '</td></tr>';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Téléphone:</td><td style="padding: 10px 0; font-weight: bold; color: #333;">' . esc($reservation['client_telephone'] ?? 'Non fourni') . '</td></tr>';
        $html .= '</table>';
        $html .= '</div>';

        $html .= '<div style="background-color: #e3f2fd; padding: 20px; border-radius: 5px; margin: 20px 0;">';
        $html .= '<h3 style="color: #333; margin-top: 0;">Détails de la réservation</h3>';
        $html .= '<table style="width: 100%; border-collapse: collapse;">';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Appartement:</td><td style="padding: 10px 0; font-weight: bold; color: #333;">' . esc($appartement['adresse']) . '</td></tr>';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Date d\'arrivée:</td><td style="padding: 10px 0; font-weight: bold; color: #333;">' . date('d/m/Y', strtotime($reservation['date_debut'])) . '</td></tr>';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Date de départ:</td><td style="padding: 10px 0; font-weight: bold; color: #333;">' . date('d/m/Y', strtotime($reservation['date_fin'])) . '</td></tr>';
        $html .= '<tr><td style="padding: 10px 0; color: #666;">Montant total:</td><td style="padding: 10px 0; font-weight: bold; color: #4CAF50; font-size: 18px;">' . number_format($reservation['montant_total'], 0, ',', ' ') . ' FCFA</td></tr>';
        $html .= '</table>';
        $html .= '</div>';

        if (!empty($reservation['notes'])) {
            $html .= '<div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;">';
            $html .= '<p style="margin: 0; color: #856404;"><strong>Notes du client:</strong></p>';
            $html .= '<p style="margin: 10px 0 0 0; color: #856404;">' . nl2br(esc($reservation['notes'])) . '</p>';
            $html .= '</div>';
        }

        $html .= '<div style="text-align: center; margin-top: 30px;">';
        $html .= '<a href="' . base_url('admin/reservations') . '" style="background-color: #2196F3; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Voir dans l\'admin</a>';
        $html .= '</div>';

        $html .= '</div>';

        $html .= $this->getEmailFooter();

        return $html;
    }

    /**
     * En-tête HTML pour les emails
     */
    private function getEmailHeader()
    {
        $html = '<!DOCTYPE html>';
        $html .= '<html lang="fr">';
        $html .= '<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>';
        $html .= '<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">';
        $html .= '<div style="max-width: 600px; margin: 20px auto; background-color: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">';

        // Ajouter le logo s'il est disponible
        if ($this->logoBase64) {
            $html .= '<div style="text-align: center; padding: 20px; background-color: #f8f9fa; border-bottom: 3px solid #d29751;">';
            $html .= '<img src="' . $this->logoBase64 . '" alt="' . esc($this->structureParams['structure_name']) . '" style="max-width: 200px; height: auto;">';
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * Pied de page HTML pour les emails
     */
    private function getEmailFooter()
    {
        $html = '<div style="background-color: #333; color: white; padding: 20px; text-align: center;">';
        $html .= '<p style="margin: 0; font-size: 16px; font-weight: bold;">' . esc($this->structureParams['structure_name']) . '</p>';

        if (!empty($this->structureParams['structure_address'])) {
            $html .= '<p style="margin: 5px 0; font-size: 14px;">' . esc($this->structureParams['structure_address']) . '</p>';
        }

        $html .= '<p style="margin: 5px 0; font-size: 14px;">';
        if (!empty($this->structureParams['structure_phone'])) {
            $html .= 'Tél: ' . esc($this->structureParams['structure_phone']) . ' | ';
        }
        if (!empty($this->structureParams['structure_email'])) {
            $html .= 'Email: ' . esc($this->structureParams['structure_email']);
        }
        $html .= '</p>';

        if (!empty($this->structureParams['structure_website'])) {
            $html .= '<p style="margin: 10px 0 0 0; font-size: 12px;">';
            $html .= '<a href="' . esc($this->structureParams['structure_website']) . '" style="color: #4CAF50; text-decoration: none;">' . esc($this->structureParams['structure_website']) . '</a>';
            $html .= '</p>';
        }

        $html .= '<p style="margin: 15px 0 0 0; font-size: 11px; color: #999;">Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>';
        $html .= '</div>';
        $html .= '</div></body></html>';

        return $html;
    }
}
