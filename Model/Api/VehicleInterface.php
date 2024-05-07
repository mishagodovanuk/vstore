<?php

namespace Model\Api;

interface VehicleInterface
{
    public function getId(): int | null;
    public function setId($id);

    public function getName(): string | null;

    public function setName($name);
}