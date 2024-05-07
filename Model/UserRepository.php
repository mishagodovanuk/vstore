<?php

namespace Model;

use Model\Api\UserInterface;
use Model\Role;
use Vstore\Router\Model\AbstractRepository;
use Vstore\Router\Model\AbstractModel;
use Model\User;
use Model\Api\UserRepositoryInterface;
use Model\RoleRepository;

/**
 *
 */
class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    /**
     * @var \Model\RoleRepository
     */
    protected RoleRepository $roleRepository;

    /**
     * @param \Model\RoleRepository $roleRepository
     */
    public function __construct(
        RoleRepository $roleRepository,
    ) {
        $this->roleRepository = $roleRepository;
        $this->setInstance(User::class);
    }

    /**
     * @param $email
     * @return mixed
     */
    public function getByEmail($email)
    {
        $data = $this->getConnect()->query("SELECT * FROM " . $this->getInstance()::TABLE_NAME . " WHERE email = '$email'")
            ->fetchAll();
        $model = new $this->instance();
        $model->setData(array_shift($data));

        if ($model->getData('id')) {
            $role = $this->roleRepository->getByUserId($model->getId());
            $model->setRole($role->getData('role'));
        }

        return $model;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        $data = $this->getConnect()->query("SELECT * FROM " . $this->getInstance()::TABLE_NAME . " WHERE id = '$id'")
            ->fetchAll();
        $model = new $this->instance();
        $model->setData(array_shift($data));

        if ($model->getData('id')) {
            $role = $this->roleRepository->getByUserId($id);
            $model->setRole($role->getData('role'));
        }

        return $model;
    }

    /**
     * @param $model
     * @return AbstractModel|false
     */
    public function save($model): AbstractModel | false
    {
        $data = $model->getData();
        $role = 'user';

        if (array_key_exists('role', $data)) {
            $role = $data['role'];
            unset($data['role']);
        }
        //Do not run other repositories with opened transaction inside other transaction
        $fields = implode(',', array_keys($data));
        $values = implode("','", array_values($data));

        $this->startTransaction();

        try {
            $this->getConnect()
                ->query("INSERT INTO " . $this->getInstance()::TABLE_NAME . " ($fields) VALUES ('$values')");
            $model->setData('id', $this->getConnect()->lastInsertId());

        } catch (\Exception $e) {
            $this->rollbackTransaction();

            return false;
        }

        $this->endTransaction();

        try {
            $roleModel = new Role();
            $roleModel->setData('user_id', $model->getData('id'));
            $roleModel->setData('role', $role);
            $this->roleRepository->save($roleModel);
        } catch (\Exception $e) {
        }

        return $model;
    }
}
