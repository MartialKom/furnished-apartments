<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $protocol = 'smtp';
    public string $SMTPHost = '';
    public string $SMTPUser = '';
    public string $SMTPPass = '';
    public int $SMTPPort = 465;
    public string $SMTPCrypto = 'ssl';
    public int $SMTPTimeout = 60;
    public string $mailType = 'html';
    public string $charset = 'UTF-8';
    public bool $wordWrap = true;
    public int $wrapChars = 70;
    public string $fromEmail = '';
    public string $fromName = 'Système de Gestion - Appartements Meublés';

    public function __construct()
    {
        parent::__construct();

        // Charger les paramètres SMTP depuis les variables d'environnement
        $this->SMTPHost = getenv('SMTP_HOST') ?: 'smtp-fr.securemail.pro';
        $this->SMTPUser = getenv('SMTP_USER') ?: 'contact@nsenoutower.com';
        $this->SMTPPass = getenv('SMTP_PASS') ?: '';
        $this->SMTPPort = (int)(getenv('SMTP_PORT') ?: 465);
        $this->SMTPCrypto = getenv('SMTP_CRYPTO') ?: 'ssl';
        $this->fromEmail = getenv('SMTP_USER') ?: 'contact@nsenoutower.com';
    }
    public string $newline = "\r\n";
    public string $crlf = "\r\n";
    public bool $validate = true;
    public int $priority = 3;
    public bool $BCCBatchMode = false;
    public int $BCCBatchSize = 200;
    public bool $DSN = false;
}