<?php

declare(strict_types=1);

namespace Vstore\Router;

/**
 * Class RouterPermission
 */
class RouterPermission
{
    /**
     * Permission constants
     */
    public const CREATE_PERMISSION = 'create';

    /**
     * Permission constants
     */
    public const READ_PERMISSION = 'read';

    /**
     * Permission constants
     */
    public const UPDATE_PERMISSION = 'update';

    /**
     * Permission constants
     */
    public const DELETE_PERMISSION = 'delete';

    /**
     * Permission constants
     */
    public const ALL_PERMISSIONS = [
        self::CREATE_PERMISSION,
        self::READ_PERMISSION,
        self::UPDATE_PERMISSION,
        self::DELETE_PERMISSION,
    ];

    /**
     * Store as routerId => permission array
     *
     * Set up: $router->post()->setPermission('admin.view'); also you can set 'admin.all' for all permissions
     *
     * Example: ['0' => ['admin' => 'view'] where 0 = routerId
     * Also for several permissions: ['0' => ['admin' => ['create', 'read', 'delete', 'update'], ['user' => ['view']]]
     *
     * @var array
     */
    protected array $permissions = [];

    /**
     * @param string|int $routeId
     * @param string $permission
     * @return void
     */
    public function setPermission(string|int $routeId, string $permission): void
    {
        $rolePermissions = explode('.', $permission);
        $segments = explode('|', $rolePermissions[1]);
        $role = $rolePermissions[0];

        if (in_array('all', $segments)) {
            $this->permissions[$routeId][$role] = self::ALL_PERMISSIONS;
            return;
        }

        if (count($segments) < 0) {
            $this->permissions[$routeId][$role] = $segments[0];
        } else {
            // Append each permission to the role's permissions
            foreach ($segments as $permission) {
                if (!isset($this->permissions[$routeId][$role])) {
                    $this->permissions[$routeId][$role] = [];
                }
                $this->permissions[$routeId][$role][] = $permission;
            }
        }
    }

    /**
     * @param string|int $routeId
     * @return bool
     */
    public function hasPermission(string|int $routeId): bool
    {
        return isset($this->permissions[$routeId]);
    }

    /**
     * @param string|int $routeId
     * @return array|null
     */
    public function getPermission(string|int $routeId): ?array
    {
        return $this->permissions[$routeId] ?? null;
    }

    /**
     * @return array
     */
    public function getAllPermissions(): array
    {
        return $this->permissions;
    }
}
