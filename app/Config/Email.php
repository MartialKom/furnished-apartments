<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $protocol = 'smtp';
    public string $SMTPHost = 'smtp.gmail.com';
    public string $SMTPUser = '';
    public string $SMTPPass = '';
    public int $SMTPPort = 587;
    public string $SMTPCrypto = 'tls';
    public string $mailType = 'text';
    public string $charset = 'UTF-8';
    public bool $wordWrap = true;
    public int $wrapChars = 70;
    public string $fromEmail = '';
    public string $fromName = 'Système de Gestion - Appartements Meublés';
    public string $newline = "\r\n";
    public string $crlf = "\r\n";
    public bool $validate = true;
    public int $priority = 3;
    public bool $BCCBatchMode = false;
    public int $BCCBatchSize = 200;
    public bool $DSN = false;
}