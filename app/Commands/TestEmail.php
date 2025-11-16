<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\NotificationService;

class TestEmail extends BaseCommand
{
    protected $group       = 'app';
    protected $name        = 'test:email';
    protected $description = 'Teste la configuration email et l\'envoi de notifications.';

    public function run(array $params)
    {
        CLI::write('=== TEST DE LA CONFIGURATION EMAIL ===', 'green');
        
        $notificationService = new NotificationService();
        
        // 1. Vérifier la configuration email
        CLI::write("\n1. Vérification de la configuration email...");
        
        $emailConfig = config('Email');
        CLI::write("  - Protocol: " . ($emailConfig->protocol ?? 'Non configuré'));
        CLI::write("  - SMTP Host: " . ($emailConfig->SMTPHost ?? 'Non configuré'));
        CLI::write("  - SMTP Port: " . ($emailConfig->SMTPPort ?? 'Non configuré'));
        CLI::write("  - SMTP User: " . ($emailConfig->SMTPUser ?? 'Non configuré'));
        CLI::write("  - SMTP Pass: " . (empty($emailConfig->SMTPPass) ? 'Non configuré' : '***configuré***'));
        
        // 2. Test d'envoi d'email simple
        CLI::write("\n2. Test d'envoi d'email...");
        
        $emailTest = $params[0] ?? 'test@example.com';
        CLI::write("  Envoi vers: {$emailTest}");
        
        $subject = "Test de configuration email - " . date('Y-m-d H:i:s');
        $message = "Ceci est un test de configuration email.\n\n";
        $message .= "Date: " . date('Y-m-d H:i:s') . "\n";
        $message .= "Serveur: " . ($_SERVER['SERVER_NAME'] ?? 'Local') . "\n";
        $message .= "Application: Système de gestion d'appartements\n\n";
        $message .= "Si vous recevez cet email, la configuration SMTP fonctionne correctement.";
        
        try {
            $result = $notificationService->sendEmail($emailTest, $subject, $message);
            
            if ($result) {
                CLI::write("  ✓ Email envoyé avec succès", 'green');
            } else {
                CLI::write("  ✗ Échec de l'envoi d'email", 'red');
                CLI::write("  Vérifiez la configuration SMTP dans app/Config/Email.php", 'yellow');
            }
        } catch (\Exception $e) {
            CLI::write("  ✗ Exception lors de l'envoi: " . $e->getMessage(), 'red');
        }
        
        // 3. Test de template d'email de notification
        CLI::write("\n3. Test de template de notification...");
        
        $templateData = [
            'locataire_nom' => 'Jean Dupont',
            'locataire_email' => $emailTest,
            'appartement_adresse' => '123 Rue de la Paix, Paris',
            'montant_du' => 500000,
            'date_echeance' => date('Y-m-d', strtotime('+3 days')),
            'type_notification' => 'rappel'
        ];
        
        $subjectTemplate = "Rappel: Échéance de loyer proche pour l'appartement " . $templateData['appartement_adresse'];
        $messageTemplate = $this->generateNotificationTemplate($templateData);
        
        CLI::write("  Template généré pour: {$templateData['locataire_nom']}");
        CLI::write("  Montant: " . number_format($templateData['montant_du'], 0, ',', ' ') . " FCFA");
        CLI::write("  Échéance: " . date('d/m/Y', strtotime($templateData['date_echeance'])));
        
        // 4. Recommandations de configuration
        CLI::write("\n4. Recommandations de configuration SMTP:");
        CLI::write("  Pour configurer SMTP, modifiez app/Config/Email.php:");
        CLI::write("  ");
        CLI::write("  public \$protocol = 'smtp';");
        CLI::write("  public \$SMTPHost = 'smtp.gmail.com';  // ou votre serveur SMTP");
        CLI::write("  public \$SMTPPort = 587;");
        CLI::write("  public \$SMTPUser = 'votre-email@domain.com';");
        CLI::write("  public \$SMTPPass = 'votre-mot-de-passe';");
        CLI::write("  public \$SMTPCrypto = 'tls';");
        CLI::write("  public \$SMTPTimeout = 60;");
        CLI::write("  ");
        CLI::write("  Exemples de serveurs SMTP:");
        CLI::write("  - Gmail: smtp.gmail.com:587");
        CLI::write("  - Outlook: smtp-mail.outlook.com:587");
        CLI::write("  - Yahoo: smtp.mail.yahoo.com:587");
        CLI::write("  - Serveur local: localhost:25");
        
        // 5. Test de configuration avec différents protocoles
        CLI::write("\n5. Test de configuration alternative...");
        CLI::write("  Si SMTP ne fonctionne pas, vous pouvez utiliser:");
        CLI::write("  - sendmail (Linux/Mac): \$protocol = 'sendmail'");
        CLI::write("  - mail (Windows): \$protocol = 'mail'");
        CLI::write("  - Logging: \$protocol = 'log' (pour déboguer)");
        
        CLI::write("\n=== FIN DU TEST EMAIL ===", 'green');
    }
    
    private function generateNotificationTemplate($data)
    {
        $template = "Bonjour " . $data['locataire_nom'] . ",\n\n";
        
        if ($data['type_notification'] === 'rappel') {
            $template .= "Ceci est un rappel amical que votre loyer de " . 
                        number_format($data['montant_du'], 0, ',', ' ') . 
                        " FCFA pour l'appartement " . $data['appartement_adresse'] . 
                        " est dû le " . date('d/m/Y', strtotime($data['date_echeance'])) . ".\n\n";
        } else {
            $template .= "Votre loyer de " . 
                        number_format($data['montant_du'], 0, ',', ' ') . 
                        " FCFA pour l'appartement " . $data['appartement_adresse'] . 
                        " est en retard depuis le " . date('d/m/Y', strtotime($data['date_echeance'])) . ".\n\n";
        }
        
        $template .= "Veuillez effectuer le paiement à temps pour éviter les frais de retard.\n\n";
        $template .= "Merci,\nVotre gestionnaire immobilier.";
        
        return $template;
    }
}