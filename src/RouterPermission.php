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
     * @param string|int $route
     * @param string $permission
     * @return void
     */
    public function setPermission(string|int $route, string $permission): void
    {
        $parts = explode('|', $permission);

        foreach ($parts as $part) {
            $segments = explode('.', $part);
            $role = $segments[0];
            $permission = $segments[1];

            if ($permission === 'all') {
                $this->permissions[$route][$role] = self::ALL_PERMISSIONS;
            } else {
                if (isset($this->permissions[$route][$role])) {
                    $this->permissions[$route][$role][] = $permission;
                } else {
                    $this->permissions[$route][$role] = [$permission];
                }
            }
        }
    }

    /**
     * @param string|int $route
     * @return bool
     */
    public function hasPermission(string|int $route): bool
    {
        return isset($this->permissions[$route]);
    }

    /**
     * @param string|int $route
     * @return array|null
     */
    public function getPermission(string|int $route): ?array
    {
        return $this->permissions[$route] ?? null;
    }

    /**
     * @return array
     */
    public function getAllPermissions(): array
    {
        return $this->permissions;
    }
}
