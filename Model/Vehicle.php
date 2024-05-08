<?php

declare(strict_types=1);

namespace Model;

use Vstore\Router\Model\AbstractModel;
use Model\Api\VehicleInterface;

/**
 * Class Vehicle
 */
class Vehicle extends AbstractModel implements VehicleInterface
{
    /**
     * @var string TABLE_NAME
     */
    public const TABLE_NAME = 'vehicle';

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
     * @return int|null
     */
    public function getId(): ?int
    {
        return (int)$this->getData('id');
    }

    /**
     * @param $id
     * @return void
     */
    public function setId($id): void
    {
        $this->setData('id', $id);
    }

    /**
     * @return string|null
     */
    public function getName(): string|null
    {
        return $this->getData('name');
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->setData('name', $name);
    }
}
