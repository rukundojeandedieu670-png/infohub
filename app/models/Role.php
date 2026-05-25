<?php
/**
 * Role Model
 */

require_once __DIR__ . '/../../core/Model.php';

class Role extends Model {
    protected $table = 'roles';

    /**
     * Find role by name
     */
    public function getByName($name) {
        $this->db->prepare("SELECT * FROM {$this->table} WHERE name = ? LIMIT 1");
        $this->db->bind('s', $name);
        $this->db->execute();
        return $this->db->single();
    }

    /**
     * Resolve the current role's assigned permissions.
     */
    public function getEffectivePermissions($roleIdentifier) {
        $role = $this->findRole($roleIdentifier);
        if (!$role) {
            return [];
        }

        return $this->parsePermissions($role['permissions'] ?? null);
    }

    /**
     * Load role ancestry chain from the current role to the top-level role.
     * This is used to determine whether a higher-level role can act as a lower one.
     */
    public function getRoleHierarchy($roleIdentifier) {
        $hierarchy = [];
        $role = $this->findRole($roleIdentifier);

        while ($role) {
            $hierarchy[] = $role;

            if (empty($role['parent_id'])) {
                break;
            }

            $role = $this->findById((int)$role['parent_id']);
        }

        return $hierarchy;
    }

    /**
     * Check whether the current role is the required role or is higher in the delegation chain.
     */
    public function canActAs($currentRoleIdentifier, $requiredRoleIdentifier) {
        $currentRole = $this->findRole($currentRoleIdentifier);
        $requiredRole = $this->findRole($requiredRoleIdentifier);

        if (!$currentRole || !$requiredRole) {
            return false;
        }

        if ($currentRole['id'] === $requiredRole['id']) {
            return true;
        }

        $requiredHierarchy = $this->getRoleHierarchy($requiredRole['id']);
        foreach ($requiredHierarchy as $role) {
            if ($role['id'] === $currentRole['id']) {
                return true;
            }
        }

        return false;
    }

    /**
     * Attempt to resolve a role by ID or name.
     */
    public function findRole($roleIdentifier) {
        if (is_int($roleIdentifier) || ctype_digit((string)$roleIdentifier)) {
            return $this->findById((int)$roleIdentifier);
        }

        return $this->getByName($roleIdentifier);
    }

    /**
     * Parse permissions stored in JSON or array form.
     */
    protected function parsePermissions($rawPermissions) {
        if (is_array($rawPermissions)) {
            return $rawPermissions;
        }

        if (is_string($rawPermissions) && trim($rawPermissions) !== '') {
            $decoded = json_decode($rawPermissions, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }

    /**
     * Determine whether a permission entry matches a requested action.
     */
    public function permissionMatchesAction($permission, $action) {
        if ($permission === '*') {
            return true;
        }

        if ($permission === $action) {
            return true;
        }

        if (substr($permission, -2) === '.*') {
            $prefix = substr($permission, 0, -1);
            return strpos($action, $prefix) === 0;
        }

        if (strpos($action, $permission . '.') === 0) {
            return true;
        }

        $scopeMap = [
            'posts' => 'posts.',
            'comments' => 'comments.',
            'jobs' => 'jobs.',
            'businesses' => 'businesses.',
            'applications' => 'applications.',
            'users' => 'users.',
            'profile' => 'profile.',
            'profiles' => 'profile.'
        ];

        if (isset($scopeMap[$permission]) && strpos($action, $scopeMap[$permission]) === 0) {
            return true;
        }

        return false;
    }
}
