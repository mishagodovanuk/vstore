<?php

declare(strict_types=1);

namespace Model;

use Model\Vehicle;
use Vstore\Router\Model\AbstractRepository;
use Model\Api\VehicleRepositoryInterface;

/**
 * Class VehicleRepository
 */
class VehicleRepository extends AbstractRepository implements VehicleRepositoryInterface
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * VehicleRepository constructor.
     */
    public function __construct()
    {
        $this->setInstance(Vehicle::class);
    }

    /**
     * @return array
     */
    public function list(): array
    {
        $query = $this->getConnect()->query('SELECT * FROM ' . Vehicle::TABLE_NAME);
        $items = $query->fetchAll();

        if (!empty($items)) {
            $this->initItems($items);
        }

        return  $this->getItems();
    }

    /**
     * @return array
     */
    protected function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $data
     * @return static
     */
    private function initItems(array $data): static
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
