<?php

declare(strict_types=1);

namespace Model\Api;

use Model\Api\UserInterface;
use Vstore\Router\Model\AbstractModel;

/**
 * Interface UserRepositoryInterface
 */
interface UserRepositoryInterface
{
    /**
     * @param AbstractModel $model
     * @return UserInterface|AbstractModel
     */
    public function get(AbstractModel $model): ?AbstractModel;

    /**
     * @param AbstractModel $model
     * @return bool|AbstractModel
     */
    public function save(AbstractModel $model): bool|AbstractModel;

    /**
     * @param AbstractModel $model
     * @return AbstractModel|bool
     */
    public function delete(AbstractModel $model): AbstractModel|bool;

    /**
     * @return array
     */
    public function list(): array;
}
