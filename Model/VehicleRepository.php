<?php

namespace Model;

use Model\Vehicle;
use Vstore\Router\Model\AbstractRepository;
use Model\Api\VehicleRepositoryInterface;

class VehicleRepository extends AbstractRepository implements VehicleRepositoryInterface
{
    protected $items = [];

    public function __construct()
    {
        $this->setInstance(Vehicle::class);
    }

    public function list()
    {
        $query = $this->getConnect()->query('SELECT * FROM ' . Vehicle::TABLE_NAME);
        $items = $query->fetchAll();

        if (!empty($items)) {
            $this->initItems($items);
        }

        return  $this->getItems();
    }

    protected function getItems()
    {
        return $this->items;
    }

    private function initItems(array $data)
    {
        foreach ($data as $item) {
            try {
                $instance = new $this->instance();
                $instance->setData($item);
                $this->items[] = $instance;
            } catch (\Exception $e) {
                continue;
            }
        }

        return $this;
    }
}