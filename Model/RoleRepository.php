<?php

declare(strict_types=1);

namespace Model;

use Vstore\Router\Model\AbstractRepository;
use Model\Role;

/**
 * Class RoleRepository
 */
class RoleRepository extends AbstractRepository
{
    /**
     * Construct for RoleRepository
     */
    public function __construct()
    {
         $this->setInstance(Role::class);
    }

    /**
     * @param $userId
     * @return Role
     */
    public function getByUserId($userId): Role
    {
        $data = $this->getConnect()->query("SELECT * FROM " . $this->getInstance()::TABLE_NAME . " WHERE user_id = " . $userId)
            ->fetchAll();
        $model = new $this->instance();
        $model->setData(array_shift($data));

        return $model;
    }
}
