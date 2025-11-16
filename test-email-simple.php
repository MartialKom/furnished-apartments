<?php

// Script de test simple pour l'envoi d'email
require __DIR__ . '/vendor/autoload.php';

// Charger les constantes de CodeIgniter
define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('APPPATH', ROOTPATH . 'app' . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', ROOTPATH . 'vendor/codeigniter4/framework/system' . DIRECTORY_SEPARATOR);
define('FCPATH', ROOTPATH . 'public' . DIRECTORY_SEPARATOR);
define('WRITEPATH', ROOTPATH . 'writable' . DIRECTORY_SEPARATOR);

// Charger les variables d'environnement depuis .env
$envFile = ROOTPATH . '.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

echo "=== TEST D'ENVOI D'EMAIL ===\n\n";

// Configuration SMTP
$config = [
    'protocol' => 'smtp',
    'SMTPHost' => getenv('SMTP_HOST'),
    'SMTPUser' => getenv('SMTP_USER'),
    'SMTPPass' => getenv('SMTP_PASS'),
    'SMTPPort' => getenv('SMTP_PORT'),
    'SMTPCrypto' => getenv('SMTP_CRYPTO'),
    'mailType' => 'text',
    'charset' => 'UTF-8',
    'newline' => "\r\n",
    'SMTPTimeout' => 60
];

echo "Configuration SMTP:\n";
echo "  Host: " . $config['SMTPHost'] . "\n";
echo "  Port: " . $config['SMTPPort'] . "\n";
echo "  User: " . $config['SMTPUser'] . "\n";
echo "  Crypto: " . $config['SMTPCrypto'] . "\n\n";

// Créer l'instance d'email
$email = \Config\Services::email($config);

// Préparer le message
$to = $argv[1] ?? 'contact@nsenoutower.com';
$subject = "Test d'envoi d'email - " . date('Y-m-d H:i:s');
$message = "Bonjour,\n\n";
$message .= "Ceci est un email de test envoyé depuis le système de gestion d'appartements meublés.\n\n";
$message .= "Date/Heure: " . date('Y-m-d H:i:s') . "\n";
$message .= "Système: Furnished Apartments Management\n\n";
$message .= "Si vous recevez cet email, la configuration SMTP fonctionne correctement !\n\n";
$message .= "Cordialement,\n";
$message .= "Le système de gestion";

echo "Envoi de l'email à: $to\n";
echo "Sujet: $subject\n\n";

try {
    $email->setFrom($config['SMTPUser'], 'Système de Gestion - Appartements Meublés');
    $email->setTo($to);
    $email->setSubject($subject);
    $email->setMessage($message);

    echo "Tentative d'envoi...\n";

    if ($email->send()) {
        echo "\n✓ EMAIL ENVOYÉ AVEC SUCCÈS !\n";
        echo "Vérifiez votre boîte mail: $to\n\n";
    } else {
        echo "\n✗ ÉCHEC DE L'ENVOI\n";
        echo "Erreur:\n";
        echo $email->printDebugger(['headers', 'subject', 'body']) . "\n";
    }
} catch (\Exception $e) {
    echo "\n✗ EXCEPTION: " . $e->getMessage() . "\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n=== FIN DU TEST ===\n";
