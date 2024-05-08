<?php

declare(strict_types=1);

namespace Model\Api;

/**
 * Interface UserInterface
 */
interface UserInterface
{
    /**
     * @return int|string|null
     */
    public function getId(): int|string|null;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * @return string|null
     */
    public function getPassword(): ?string;

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
