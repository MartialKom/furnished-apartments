<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Route to permission mapping
     * Maps route arguments to permission codes
     */
    protected $routePermissions = [
        // Navigation
        'dashboard' => 'view_dashboard',

        // Gestion des Appartements
        'appartements' => 'view_appartements',
        'reservations' => 'view_reservations',
        'locataires' => 'view_locataires',
        'paiements' => 'view_paiements',
        'contrats' => 'view_contrats',
        'paiements-mensuels' => 'view_paiements_mensuels',
        'factures-eau' => 'view_factures_eau',

        // Location de Voitures
        'voitures' => 'view_voitures',
        'locations-voitures' => 'view_locations_voitures',

        // Gestion du Stock
        'stock' => 'view_stock_dashboard',
        'produits' => 'view_produits',
        'approvisionnements' => 'view_approvisionnements',
        'sorties' => 'view_sorties',
        'inventaires' => 'view_inventaires',

        // Rapports & Analyse
        'rapports' => 'view_rapports',
        'analytics' => 'view_analytics',
        'contrats-location' => 'view_contrats_location',

        // Système
        'utilisateurs' => 'view_utilisateurs',
        'roles' => 'view_roles',
        'settings' => 'view_parametres',
        'parametres' => 'view_parametres'
    ];

    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Check if user is logged in
        if (!$session->get('is_logged_in')) {
            if ($request->isAJAX()) {
                return service('response')->setJSON([
                    'success' => false,
                    'message' => 'Session expirée. Veuillez vous reconnecter.',
                    'redirect' => '/admin/login'
                ])->setStatusCode(401);
            }
            return redirect()->to('/admin/login')
                ->with('error', 'Vous devez vous connecter pour accéder à cette page.');
        }

        // Super admin has access to everything - bypass all permission checks
        if ($session->get('is_super_admin')) {
            return;
        }

        // Check permissions if specified in arguments
        if ($arguments && count($arguments) > 0) {
            $requiredArg = $arguments[0];

            // Special case: "admin" filter requires system permissions
            if ($requiredArg === 'admin') {
                // User must have at least one of these system permissions
                $adminPermissions = ['view_utilisateurs', 'view_roles', 'view_parametres', 'manage_utilisateurs', 'manage_roles'];
                $hasAdminPermission = false;

                foreach ($adminPermissions as $perm) {
                    if ($this->hasPermission($session, $perm)) {
                        $hasAdminPermission = true;
                        break;
                    }
                }

                if (!$hasAdminPermission) {
                    log_message('warning', "User {$session->get('user_id')} denied access to admin route - no admin permissions");
                    return $this->accessDenied($request);
                }

                return; // Admin permission granted
            }

            // Map argument to permission code
            $requiredPermission = isset($this->routePermissions[$requiredArg])
                ? $this->routePermissions[$requiredArg]
                : $requiredArg;

            // Check if user has the required permission
            if (!$this->hasPermission($session, $requiredPermission)) {
                log_message('warning', "User {$session->get('user_id')} denied access - missing permission: {$requiredPermission}");
                return $this->accessDenied($request);
            }

            return; // Permission granted
        }

        // Check URI-based permissions for routes without explicit arguments
        $uri = $request->getUri()->getPath();
        $permissionRequired = $this->getPermissionFromUri($uri);

        if ($permissionRequired && !$this->hasPermission($session, $permissionRequired)) {
            return $this->accessDenied($request);
        }
    }

    /**
     * Check if user has a specific permission
     */
    protected function hasPermission($session, string $permissionCode): bool
    {
        $permissions = $session->get('user_permissions') ?? [];
        return in_array($permissionCode, $permissions);
    }

    /**
     * Get permission code from URI
     */
    protected function getPermissionFromUri(string $uri): ?string
    {
        foreach ($this->routePermissions as $route => $permission) {
            if (strpos($uri, $route) !== false) {
                return $permission;
            }
        }
        return null;
    }

    /**
     * Return access denied response
     */
    protected function accessDenied(RequestInterface $request)
    {
        if ($request->isAJAX()) {
            return service('response')->setJSON([
                'success' => false,
                'message' => 'Vous n\'avez pas les permissions nécessaires pour accéder à cette ressource.',
                'redirect' => '/admin/dashboard'
            ])->setStatusCode(403);
        }
        return redirect()->to('/admin/dashboard')
            ->with('error', 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
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
