<?php

declare(strict_types=1);

namespace Model;

use Vstore\Router\Model\AbstractModel;

/**
 * Class Role
 */
class Role extends AbstractModel
{
    /**
     * Table const
     */
    public const TABLE_NAME = 'roles';

    /**
     * Primary key const
     */
    public const PRIMARY_KEY = 'id';

    /**
     * @var string
     */
    public string $id;

    /**
     * @var string
     */
    public string $role;

    /**
     * @var string
     */
    public string $user_id;

    /**
     * @return array|mixed|null
     */
    public function getId(): mixed
    {
        return $this->getData('id');
    }

    /**
     * @param string|int $id
     * @return void
     */
    public function setId(string|int $id): void
    {
        $this->setData('id', $id);
    }

    /**
     * @return array|mixed|null
     */
    public function getRole(): mixed
    {
        return $this->getData('role');
    }

    /**
     * @param string $role
     * @return void
     */
    public function setRole(string $role): void
    {
        $this->setData('role', $role);
    }

    /**
     * @return array|mixed|null
     */
    public function getUserId(): mixed
    {
        return $this->getData('user_id');
    }

    /**
     * @param string|int $user_id
     * @return void
     */
    public function setUserId(string|int $user_id): void
    {
        $this->setData('user_id', $user_id);
    }
}
