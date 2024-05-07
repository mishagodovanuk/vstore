<?php

namespace Model;

use Vstore\Router\Model\AbstractModel;
use Model\Api\VehicleInterface;
class Vehicle extends AbstractModel implements VehicleInterface
{
    public const TABLE_NAME = 'vehicle';

    public const PRIMARY_KEY = 'id';

    public $id;

    public $name;

    public function getId(): int|null
    {
        return (int)$this->getData('id');
    }

    public function setId($id)
    {
        $this->setData('id', $id);
    }

    public function getName(): string|null
    {
        return $this->getData('name');
    }

    public function setName($name)
    {
        $this->setData('name', $name);
    }
}
