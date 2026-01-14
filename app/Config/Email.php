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
    public string $fromName = 'T-Lodge - Système de Gestion';

    public function __construct()
    {
        parent::__construct();

        try {
            // Charger les paramètres SMTP depuis la base de données avec fallback .env
            $structureParamModel = new \App\Models\StructureParamModel();
            $smtpParams = $structureParamModel->getSmtpParams();

            // Appliquer les paramètres (BD prioritaire, .env en fallback via getSmtpParams)
            $this->SMTPHost = $smtpParams['smtp_host'] ?: 'smtp-fr.securemail.pro';
            $this->SMTPPort = (int)($smtpParams['smtp_port'] ?: 465);
            $this->SMTPUser = $smtpParams['smtp_user'] ?: 'contact@nsenoutower.com';
            $this->SMTPPass = $smtpParams['smtp_pass'] ?: '';
            $this->SMTPCrypto = $smtpParams['smtp_crypto'] ?: 'ssl';
            $this->SMTPTimeout = (int)($smtpParams['smtp_timeout'] ?: 60);
            $this->fromEmail = $smtpParams['smtp_from_email'] ?: $smtpParams['smtp_user'];
            $this->fromName = $smtpParams['smtp_from_name'] ?: 'T-Lodge - Système de Gestion';

        } catch (\Exception $e) {
            // Si erreur BD, utiliser uniquement les variables d'environnement
            log_message('warning', 'Impossible de charger config SMTP depuis BD, fallback .env : ' . $e->getMessage());

            $this->SMTPHost = getenv('SMTP_HOST') ?: 'smtp-fr.securemail.pro';
            $this->SMTPUser = getenv('SMTP_USER') ?: 'contact@nsenoutower.com';
            $this->SMTPPass = getenv('SMTP_PASS') ?: '';
            $this->SMTPPort = (int)(getenv('SMTP_PORT') ?: 465);
            $this->SMTPCrypto = getenv('SMTP_CRYPTO') ?: 'ssl';
            $this->fromEmail = getenv('SMTP_USER') ?: 'contact@nsenoutower.com';
        }
    }
    public string $newline = "\r\n";
    public string $crlf = "\r\n";
    public bool $validate = true;
    public int $priority = 3;
    public bool $BCCBatchMode = false;
    public int $BCCBatchSize = 200;
    public bool $DSN = false;
}