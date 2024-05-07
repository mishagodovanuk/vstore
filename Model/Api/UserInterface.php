<?php

namespace Model\Api;

/**
 *
 */
interface UserInterface
{
    /**
     * @return string|null
     */
    public function getId(): string | null;

    /**
     * @return string|null
     */
    public function getName(): string | null;

    /**
     * @return string|null
     */
    public function getEmail(): string | null;

    /**
     * @return string|null
     */
    public function getPassword(): string | null;

    /**
     * @param string $id
     * @return void
     */
    public function setId(string $id): void;

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void;

    /**
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void;

    /**
     * @param string $password
     * @return void
     */
    public function setPassword(string $password): void;
}
