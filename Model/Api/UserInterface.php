<?php

namespace Model\Api;

interface UserInterface
{
    public function getId(): string | null;

    public function getName(): string | null;

    public function getEmail(): string | null;

    public function getPassword(): string | null;

    public function setId(string $id): void;

    public function setName(string $name): void;

    public function setEmail(string $email): void;

    public function setPassword(string $password): void;
}
