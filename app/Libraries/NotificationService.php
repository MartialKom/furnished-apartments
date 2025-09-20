<?php

namespace App\Libraries;

use CodeIgniter\Email\Email;

class NotificationService
{
    protected $email;
    protected $config;

    public function __construct()
    {
        $this->email = \Config\Services::email();
        $this->config = config('Email');
    }
    
    /**
     * Vérifie si l'email est correctement configuré
     */
    public function isEmailConfigured()
    {
        return !empty($this->config->SMTPHost) && 
               !empty($this->config->SMTPUser) && 
               !empty($this->config->SMTPPass);
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
            $this->logNotification($locataire['email'], $sujet, $message, 'info');
            
            // Simuler l'envoi aux administrateurs
            $this->envoyerNotificationAdmin($locataire, $echeance, $contrat);
            return true; // Retourner true pour ne pas bloquer le processus
        }
        
        try {
            $this->email->clear();
            $this->email->setFrom($this->config->fromEmail ?? 'noreply@example.com', 'Système de Gestion - Appartements Meublés');
            $this->email->setTo($locataire['email']);
            $this->email->setSubject($sujet);
            $this->email->setMessage($message);
            
            $result = $this->email->send();
            
            if ($result) {
                log_message('info', "Notification échéance envoyée avec succès à {$locataire['email']}");
                // Envoyer aussi aux administrateurs
                $this->envoyerNotificationAdmin($locataire, $echeance, $contrat);
            } else {
                $error = $this->email->printDebugger(['headers']);
                log_message('error', "Échec envoi notification échéance à {$locataire['email']}: {$error}");
                $this->logNotification($locataire['email'], $sujet, $message, 'error');
            }
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', "Exception lors de l'envoi de notification échéance: " . $e->getMessage());
            $this->logNotification($locataire['email'], $sujet, $message, 'error');
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
            $this->logNotification($locataire['email'], $sujet, $message, 'warning');
            
            // Simuler l'envoi aux administrateurs
            $this->envoyerNotificationAdminRetard($locataire, $echeance, $contrat);
            return true; // Retourner true pour ne pas bloquer le processus
        }
        
        try {
            $this->email->clear();
            $this->email->setFrom($this->config->fromEmail ?? 'noreply@example.com', 'Système de Gestion - Appartements Meublés');
            $this->email->setTo($locataire['email']);
            $this->email->setSubject($sujet);
            $this->email->setMessage($message);
            
            $result = $this->email->send();
            
            if ($result) {
                log_message('info', "Notification retard envoyée avec succès à {$locataire['email']}");
                // Envoyer aux administrateurs
                $this->envoyerNotificationAdminRetard($locataire, $echeance, $contrat);
            } else {
                $error = $this->email->printDebugger(['headers']);
                log_message('error', "Échec envoi notification retard à {$locataire['email']}: {$error}");
                $this->logNotification($locataire['email'], $sujet, $message, 'error');
            }
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', "Exception lors de l'envoi de notification retard: " . $e->getMessage());
            $this->logNotification($locataire['email'], $sujet, $message, 'error');
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
    private function genererMessageEcheance($locataire, $echeance, $contrat)
    {
        $joursRestants = (strtotime($echeance['date_echeance']) - time()) / (60 * 60 * 24);
        $joursRestants = ceil($joursRestants);
        
        $message = "Bonjour " . $locataire['nom'] . ",\n\n";
        $message .= "Nous vous informons que votre loyer pour le mois de " . $this->formaterMoisAnnee($echeance['mois_annee']) . " arrive à échéance.\n\n";
        $message .= "Détails du paiement :\n";
        $message .= "- Montant dû : " . number_format($echeance['montant_du'], 0, ',', ' ') . " FCFA\n";
        $message .= "- Date d'échéance : " . date('d/m/Y', strtotime($echeance['date_echeance'])) . "\n";
        $message .= "- Appartement : " . $contrat['appartement_adresse'] . "\n";
        $message .= "- Jours restants : " . $joursRestants . " jour(s)\n\n";
        $message .= "Veuillez effectuer votre paiement avant la date d'échéance.\n\n";
        $message .= "Cordialement,\n";
        $message .= "L'équipe de gestion";
        
        return $message;
    }

    /**
     * Générer le message de retard pour le locataire
     */
    private function genererMessageRetard($locataire, $echeance, $contrat)
    {
        $joursRetard = (time() - strtotime($echeance['date_echeance'])) / (60 * 60 * 24);
        $joursRetard = floor($joursRetard);
        
        $message = "Bonjour " . $locataire['nom'] . ",\n\n";
        $message .= "Nous vous informons que votre loyer pour le mois de " . $this->formaterMoisAnnee($echeance['mois_annee']) . " est en retard.\n\n";
        $message .= "Détails du retard :\n";
        $message .= "- Montant dû : " . number_format($echeance['montant_du'], 0, ',', ' ') . " FCFA\n";
        $message .= "- Date d'échéance : " . date('d/m/Y', strtotime($echeance['date_echeance'])) . "\n";
        $message .= "- Jours de retard : " . $joursRetard . " jour(s)\n";
        $message .= "- Appartement : " . $contrat['appartement_adresse'] . "\n\n";
        $message .= "Veuillez régulariser votre situation dans les plus brefs délais.\n";
        $message .= "En cas de difficultés, veuillez nous contacter.\n\n";
        $message .= "Cordialement,\n";
        $message .= "L'équipe de gestion";
        
        return $message;
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
}
