<?php

namespace Model;

use Vstore\Router\Model\AbstractModel;
use Model\Api\UserInterface;

/**
 *
 */
class User extends AbstractModel implements UserInterface
{
    /**
     *
     */
    public const TABLE_NAME = 'users';

    /**
     *
     */
    public const PRIMARY_KEY = 'id';

    /**
     * @var string
     */
    public string $id;

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
    public string $role;

    /**
     * @return string|null
     */
    public function getId(): string | null
    {
        return $this->getData('id');
    }

    /**
     * @return string|null
     */
    public function getName(): string | null
    {
        return $this->getData('name');
    }

    /**
     * @return string|null
     */
    public function getEmail(): string | null
    {
        return $this->getData('email');
    }

    /**
     * @return string|null
     */
    public function getPassword(): string | null
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
     * @param string $role
     * @return void
     */
    public function setRole(string $role)
    {
        $this->setData('role', $role);
    }

    /**
     * @return array|mixed|null
     */
    public function getRole()
    {
        return $this->getData('role');
    }
}
