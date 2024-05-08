<?php

declare(strict_types=1);

namespace Model\Api;

/**
 * Interface VehicleInterface
 */
interface VehicleInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @param string|int $id
     * @return void
     */
    public function setId(string|int $id): void;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void;
}
