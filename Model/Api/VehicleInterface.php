<?php

namespace Model\Api;

/**
 *
 */
interface VehicleInterface
{
    /**
     * @return int|null
     */
    public function getId(): int | null;

    /**
     * @param $id
     * @return mixed
     */
    public function setId($id);

    /**
     * @return string|null
     */
    public function getName(): string | null;

    /**
     * @param $name
     * @return mixed
     */
    public function setName($name);
}