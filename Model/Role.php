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
     * @var string|int|null
     */
    public string|int|null $id;

    /**
     * @var string
     */
    public string $role;

    /**
     * @var string|int|null
     */
    public string|int|null $user_id;

    /**
     * @return int|string|null
     */
    public function getId(): int|string|null
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
     * @return string|null
     */
    public function getRole(): ?string
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
     * @return int|string
     */
    public function getUserId(): int|string
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
