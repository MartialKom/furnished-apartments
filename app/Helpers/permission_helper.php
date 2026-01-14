<?php

/**
 * Permission Helper
 *
 * Provides helper functions for checking user permissions throughout the application.
 * These functions should be used in controllers, views, and filters to verify access rights.
 */

if (!function_exists('hasPermission')) {
    /**
     * Check if current user has a specific permission
     *
     * @param string $permissionCode The permission code to check (e.g., 'view_dashboard')
     * @return bool True if user has the permission, false otherwise
     */
    function hasPermission(string $permissionCode): bool
    {
        $session = session();

        // Super admin has all permissions
        if ($session->get('is_super_admin')) {
            return true;
        }

        $permissions = $session->get('user_permissions') ?? [];
        return in_array($permissionCode, $permissions);
    }
}

if (!function_exists('hasAnyPermission')) {
    /**
     * Check if current user has any of the specified permissions
     *
     * @param array $permissionCodes Array of permission codes to check
     * @return bool True if user has at least one of the permissions, false otherwise
     */
    function hasAnyPermission(array $permissionCodes): bool
    {
        $session = session();

        // Super admin has all permissions
        if ($session->get('is_super_admin')) {
            return true;
        }

        $permissions = $session->get('user_permissions') ?? [];
        foreach ($permissionCodes as $code) {
            if (in_array($code, $permissions)) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('hasAllPermissions')) {
    /**
     * Check if current user has all specified permissions
     *
     * @param array $permissionCodes Array of permission codes to check
     * @return bool True if user has all permissions, false otherwise
     */
    function hasAllPermissions(array $permissionCodes): bool
    {
        $session = session();

        // Super admin has all permissions
        if ($session->get('is_super_admin')) {
            return true;
        }

        $permissions = $session->get('user_permissions') ?? [];
        foreach ($permissionCodes as $code) {
            if (!in_array($code, $permissions)) {
                return false;
            }
        }
        return true;
    }
}

if (!function_exists('isSuperAdmin')) {
    /**
     * Check if current user is a super admin
     *
     * @return bool True if user is super admin, false otherwise
     */
    function isSuperAdmin(): bool
    {
        return session()->get('is_super_admin') === true;
    }
}

if (!function_exists('canManage')) {
    /**
     * Check if current user can manage a specific module
     * This checks for the 'manage_X' permission where X is the module name
     *
     * @param string $module The module name (e.g., 'appartements', 'utilisateurs')
     * @return bool True if user can manage the module, false otherwise
     */
    function canManage(string $module): bool
    {
        return hasPermission('manage_' . $module);
    }
}

if (!function_exists('canView')) {
    /**
     * Check if current user can view a specific module
     * This checks for the 'view_X' permission where X is the module name
     *
     * @param string $module The module name (e.g., 'appartements', 'utilisateurs')
     * @return bool True if user can view the module, false otherwise
     */
    function canView(string $module): bool
    {
        return hasPermission('view_' . $module);
    }
}

if (!function_exists('getUserPermissions')) {
    /**
     * Get all permissions for the current user
     *
     * @return array Array of permission codes
     */
    function getUserPermissions(): array
    {
        $session = session();
        return $session->get('user_permissions') ?? [];
    }
}

if (!function_exists('getUserRoleName')) {
    /**
     * Get the role name of the current user
     *
     * @return string|null The role name or null if not set
     */
    function getUserRoleName(): ?string
    {
        $session = session();
        return $session->get('user_role_name');
    }
}
