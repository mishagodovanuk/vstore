<?php

declare(strict_types=1);

namespace Model;

use Model\Api\UserInterface;
use Model\Role;
use Vstore\Router\Model\AbstractRepository;
use Vstore\Router\Model\AbstractModel;
use Model\User;
use Model\Api\UserRepositoryInterface;
use Model\RoleRepository;

/**
 * Class UserRepository
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
     * @param string $email
     * @return UserInterface
     */
    public function getByEmail(string $email): UserInterface
    {
        $data = $this->getConnect()?->query("SELECT * FROM " . $this->getInstance()::TABLE_NAME . " WHERE email = '$email'")
            ->fetchAll();
        $model = new $this->instance();
        $model->setData(array_shift($data));

        if ($model->getId()) {
            $role = $this->roleRepository->getByUserId($model->getId());
            $model->setRole($role->getData('role'));
        }

        return $model;
    }

    /**
     * @param mixed $id
     * @return UserInterface
     */
    public function getById(mixed $id): UserInterface
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
     * @param AbstractModel $model
     * @return AbstractModel|bool
     */
    public function save(AbstractModel $model): AbstractModel|bool
    {
        $data = $model->getData();
        $role = 'user';
        $data['token'] = $this->generateToken();

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

    /**
     * @param \Vstore\Router\Model\AbstractModel $model
     * @return \Vstore\Router\Model\AbstractModel|false
     */
    public function update(AbstractModel $model): AbstractModel|false
    {
        $data = $model->getData();
        $data['token'] = $this->generateToken();
        $id = $data['id'];

        unset($data['id']);
        $setValues = [];

        foreach ($data as $key => $value) {
            $setValues[] = "$key = '$value'";
        }

        $setValuesStr = implode(',', $setValues);

        $this->startTransaction();

        try {
            $this->getConnect()->query("UPDATE " . $this->getInstance()::TABLE_NAME . " SET $setValuesStr WHERE id = '$id'");
        } catch (\Exception $e) {
            $this->rollbackTransaction();
        }

        $this->endTransaction();

        return $model;
    }

    /**
     * @return array
     */
    public function list(): array
    {
        $data = $this->getConnect()->query("SELECT * FROM " . $this->getInstance()::TABLE_NAME)
            ->fetchAll();
        $models = [];

        foreach ($data as $item) {
            $model = new $this->instance();
            $model->setData($item);
            $role = $this->roleRepository->getByUserId($model->getId());
            $model->setRole($role->getData('role'));
            $models[] = $model;
        }

        return $models;
    }

    /**
     * @param string $token
     * @return AbstractModel|bool
     */
    public function getUserByToken(string $token): AbstractModel|bool
    {
        $data = $this->getConnect()->query("SELECT * FROM " . $this->getInstance()::TABLE_NAME . " WHERE token = '$token'")
            ->fetchAll();

        if (empty($data)) {
            return false;
        }

        $model = new $this->instance();
        $model->setData(array_shift($data));

        if ($model->getId()) {
            $role = $this->roleRepository->getByUserId($model->getId());
            $model->setRole($role->getData('role'));
        }

        return $model;
    }

    /**
     * Used for token generation.
     *
     * @param $length
     * @return string
     */
    private function generateToken(int $length = 15): string
    {
        try {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';

            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[random_int(0, $charactersLength - 1)];
            }
        } catch (\Exception $e) {
            return '';
        }

        return $randomString;
    }
}
