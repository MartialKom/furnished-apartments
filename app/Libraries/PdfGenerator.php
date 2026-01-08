<?php

namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGenerator
{
    private $dompdf;
    private $options;

    public function __construct()
    {
        $this->options = new Options();
        $this->options->set('isHtml5ParserEnabled', true);
        $this->options->set('isRemoteEnabled', true);
        $this->options->set('defaultFont', 'DejaVu Sans');
        $this->options->set('chroot', FCPATH);

        $this->dompdf = new Dompdf($this->options);
    }

    /**
     * Génère un PDF à partir d'une vue CodeIgniter
     *
     * @param string $view Chemin de la vue
     * @param array $data Données à passer à la vue
     * @param string $filename Nom du fichier PDF
     * @param string $mode 'D' pour download, 'I' pour inline, 'S' pour string
     * @param string $paper Format de papier (A4, letter, etc.)
     * @param string $orientation 'portrait' ou 'landscape'
     * @return mixed
     */
    public function generate($view, $data = [], $filename = 'document.pdf', $mode = 'I', $paper = 'A4', $orientation = 'portrait')
    {
        // Charger la vue avec les données
        $html = view($view, $data);

        // Charger le HTML dans DomPDF
        $this->dompdf->loadHtml($html);

        // Définir le format de papier et l'orientation
        $this->dompdf->setPaper($paper, $orientation);

        // Rendre le PDF
        $this->dompdf->render();

        // Générer le fichier selon le mode
        switch ($mode) {
            case 'D': // Download
                $this->dompdf->stream($filename, ['Attachment' => true]);
                break;
            case 'I': // Inline (affichage dans le navigateur)
                $this->dompdf->stream($filename, ['Attachment' => false]);
                break;
            case 'S': // String (retourner le contenu)
                return $this->dompdf->output();
            case 'F': // File (sauvegarder sur le serveur)
                $output = $this->dompdf->output();
                file_put_contents($filename, $output);
                return $filename;
            default:
                $this->dompdf->stream($filename, ['Attachment' => false]);
        }
    }

    /**
     * Génère un PDF directement depuis du HTML
     *
     * @param string $html Contenu HTML
     * @param string $filename Nom du fichier PDF
     * @param string $mode 'D' pour download, 'I' pour inline, 'S' pour string
     * @param string $paper Format de papier
     * @param string $orientation Orientation
     * @return mixed
     */
    public function generateFromHtml($html, $filename = 'document.pdf', $mode = 'I', $paper = 'A4', $orientation = 'portrait')
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper($paper, $orientation);
        $this->dompdf->render();

        switch ($mode) {
            case 'D':
                $this->dompdf->stream($filename, ['Attachment' => true]);
                break;
            case 'I':
                $this->dompdf->stream($filename, ['Attachment' => false]);
                break;
            case 'S':
                return $this->dompdf->output();
            case 'F':
                $output = $this->dompdf->output();
                file_put_contents($filename, $output);
                return $filename;
            default:
                $this->dompdf->stream($filename, ['Attachment' => false]);
        }
    }

    /**
     * Configure les options de DomPDF
     *
     * @param string $option Nom de l'option
     * @param mixed $value Valeur de l'option
     */
    public function setOption($option, $value)
    {
        $this->options->set($option, $value);
    }
}
