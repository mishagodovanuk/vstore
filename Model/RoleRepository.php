<?php

namespace Model;

use Vstore\Router\Model\AbstractRepository;
use Model\Role;

/**
 *
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
     * @return mixed
     */
    public function getByUserId($userId): mixed
    {
        $data = $this->getConnect()->query("SELECT * FROM " . $this->getInstance()::TABLE_NAME . " WHERE user_id = " . $userId)
            ->fetchAll();
        $model = new $this->instance();
        $model->setData(array_shift($data));

        return $model;
    }
}
