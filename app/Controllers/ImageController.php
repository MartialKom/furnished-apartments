<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

/**
 * Contrôleur pour servir les images stockées dans writable/uploads
 * Nécessaire pour Docker où seul writable/ est persistant
 */
class ImageController extends BaseController
{
    /**
     * Sert une image depuis writable/uploads
     *
     * @param string $type Type d'image (appartements, voitures, parametres)
     * @param string $filename Nom du fichier
     */
    public function serve(string $type, string $filename): ResponseInterface
    {
        // Types autorisés
        $allowedTypes = ['appartements', 'voitures', 'parametres'];

        if (!in_array($type, $allowedTypes)) {
            return $this->response->setStatusCode(404, 'Type non autorisé');
        }

        // Sécuriser le nom de fichier (éviter les attaques par traversée de chemin)
        $filename = basename($filename);

        // Chemin complet vers le fichier
        $filePath = WRITEPATH . 'uploads/' . $type . '/' . $filename;

        // Vérifier si le fichier existe
        if (!file_exists($filePath)) {
            // Retourner une image par défaut selon le type
            $defaultImage = $this->getDefaultImage($type);
            if ($defaultImage && file_exists($defaultImage)) {
                $filePath = $defaultImage;
            } else {
                return $this->response->setStatusCode(404, 'Image non trouvée');
            }
        }

        // Déterminer le type MIME
        $mimeType = mime_content_type($filePath);

        // Vérifier que c'est bien une image
        if (!str_starts_with($mimeType, 'image/')) {
            return $this->response->setStatusCode(403, 'Fichier non autorisé');
        }

        // Retourner l'image avec les headers appropriés
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Length', (string) filesize($filePath))
            ->setHeader('Cache-Control', 'public, max-age=604800') // Cache 7 jours
            ->setBody(file_get_contents($filePath));
    }

    /**
     * Retourne le chemin de l'image par défaut selon le type
     */
    private function getDefaultImage(string $type): ?string
    {
        $defaults = [
            'appartements' => FCPATH . 'assets/frontend/images/default-apartment.jpg',
            'voitures' => FCPATH . 'assets/frontend/images/default-car.jpg',
            'parametres' => null
        ];

        return $defaults[$type] ?? null;
    }
}
