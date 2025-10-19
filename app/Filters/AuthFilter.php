<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Vérifier si l'utilisateur est connecté
        if (!$session->get('is_logged_in')) {
            // Si c'est une requête AJAX, retourner une réponse JSON
            if ($request->isAJAX()) {
                return service('response')->setJSON([
                    'success' => false,
                    'message' => 'Session expirée. Veuillez vous reconnecter.',
                    'redirect' => '/admin/auth/login'
                ])->setStatusCode(401);
            }
            return redirect()->to('/admin/auth/login')->with('error', 'Vous devez vous connecter pour accéder à cette page.');
        }
        
        $userRole = $session->get('user_role');
        
        // Vérifier le rôle si spécifié dans les arguments
        if ($arguments && count($arguments) > 0) {
            $requiredRole = $arguments[0];
            
            // Les admins ont accès à tout
            if ($userRole === 'admin') {
                return; // Autoriser l'accès
            }
            
            // Vérifier les permissions spécifiques
            if ($userRole === 'gestionnaire') {
                // Les gestionnaires peuvent accéder aux routes autorisées
                $allowedRoutes = [
                    'appartements', 'reservations', 'locataires', 'paiements', 'stock'
                ];

                if (in_array($requiredRole, $allowedRoutes)) {
                    return; // Autoriser l'accès
                }
            }
            
            // Accès refusé
            if ($request->isAJAX()) {
                return service('response')->setJSON([
                    'success' => false,
                    'message' => 'Vous n\'avez pas les permissions nécessaires.',
                    'redirect' => '/admin/dashboard'
                ])->setStatusCode(403);
            }
            return redirect()->to('/admin/dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires.');
        }
        
        // Vérifier les permissions pour les routes spécifiques
        $uri = $request->getUri()->getPath();
        
        // Routes interdites aux gestionnaires
        $adminOnlyRoutes = [
            'utilisateurs', 'settings'
        ];
        
        if ($userRole === 'gestionnaire') {
            foreach ($adminOnlyRoutes as $route) {
                if (strpos($uri, $route) !== false) {
                    if ($request->isAJAX()) {
                        return service('response')->setJSON([
                            'success' => false,
                            'message' => 'Accès réservé aux administrateurs.',
                            'redirect' => '/admin/dashboard'
                        ])->setStatusCode(403);
                    }
                    return redirect()->to('/admin/dashboard')->with('error', 'Accès réservé aux administrateurs.');
                }
            }
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
