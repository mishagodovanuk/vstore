<?php

declare(strict_types=1);

namespace Model\Api;

use Vstore\Router\Model\AbstractModel;

/**
 * Interface VehicleRepositoryInterface
 */
interface VehicleRepositoryInterface
{
    /**
     * @param AbstractModel $vehicle
     * @return AbstractModel|null
     */
    public function get(AbstractModel $vehicle): ?AbstractModel;

    /**
     * @param AbstractModel $vehicle
     * @return AbstractModel|bool
     */
    public function save(AbstractModel $vehicle): AbstractModel|bool;

    /**
     * @param AbstractModel $vehicle
     * @return bool
     */
    public function delete(AbstractModel $vehicle): bool;

    /**
     * @return array
     */
    public function list(): array;
}
