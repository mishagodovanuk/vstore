<?php

namespace Model;

use Vstore\Router\Model\AbstractModel;

/**
 *
 */
class Role extends AbstractModel
{
    /**
     * Table const
     */
    public const TABLE_NAME = 'roles';

    /**
     * Primary key const
     */
    public const PRIMARY_KEY = 'id';

    /**
     * @var string
     */
    public string $id;

    /**
     * @var string
     */
    public string $role;

    /**
     * @var string
     */
    public string $user_id;

    /**
     * @return array|mixed|null
     */
    public function getId(): mixed
    {
        return $this->getData('id');
    }

    /**
     * @param $id
     * @return
     */
    public function setId($id)
    {
        $this->setData('id', $id);
    }

    /**
     * @return array|mixed|null
     */
    public function getRole(): mixed
    {
        return $this->getData('role');
    }

    /**
     * @param $role
     * @return void
     */
    public function setRole($role)
    {
        $this->setData('role', $role);
    }

    /**
     * @return array|mixed|null
     */
    public function getUserId(): mixed
    {
        return $this->getData('user_id');
    }

    /**
     * @param $user_id
     * @return void
     */
    public function setUserId($user_id)
    {
        $this->setData('user_id', $user_id);
    }
}
