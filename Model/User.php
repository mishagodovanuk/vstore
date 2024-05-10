<?php

declare(strict_types=1);

namespace Model;

use Vstore\Router\Model\AbstractModel;
use Model\Api\UserInterface;

/**
 * Class User
 */
class User extends AbstractModel implements UserInterface
{
    /**
     * @var string TABLE_NAME
     */
    public const TABLE_NAME = 'users';

    /**
     * @var string PRIMARY_KEY
     */
    public const PRIMARY_KEY = 'id';

    /**
     * @var string|int
     */
    public string|int $id;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $email;

    /**
     * @var string
     */
    public string $password;

    /**
     * @var string
     */
    public string $token;

    /**
     * @var string
     */
    public string $role;

    /**
     * @return int|string|null
     */
    public function getId(): int|string|null
    {
        return $this->getData('id');
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->getData('name');
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->getData('email');
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->getData('password');
    }

    /**
     * @param string $id
     * @return void
     */
    public function setId(string $id): void
    {
        $this->setData('id', $id);
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->setData('name', $name);
    }

    /**
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->setData('email', $email);
    }

    /**
     * @param string $password
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->setData('password', $password);
    }

    /**
     * @param string $token
     * @return void
     */
    public function setToken(string $token): void
    {
        $this->setData('token', $token);
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->getData('token');
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
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->getData('role');
    }
}
