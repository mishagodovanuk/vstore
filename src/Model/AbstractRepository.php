<?php

declare(strict_types=1);

namespace Vstore\Router\Model;

use Vstore\Router\Model\AbstractModel;
use Vstore\Router\Model\AbstractConnect;

/**
 * Class AbstractRepository
 */
abstract class AbstractRepository extends AbstractConnect
{
    /**
     * @var string
     */
    public string $instance;

    /**
     * @param string $instance
     * @return void
     */
    protected function setInstance(string $instance): void
    {
        $this->instance = $instance;
    }

    /**
     * @return AbstractModel|string
     */
    protected function getInstance(): AbstractModel|string
    {
        return $this->instance;
    }

    /**
     * @param AbstractModel $model
     *
     * @return \Vstore\Router\Model\AbstractModel|bool
     */
    public function save(AbstractModel $model): AbstractModel|bool
    {
        $data = $model->getData();
        unset($data['id']);
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

        return $model;
    }

    /**
     * @param \Vstore\Router\Model\AbstractModel $model
     * @return \Vstore\Router\Model\AbstractModel|false
     */
    public function update(AbstractModel $model): AbstractModel|false
    {
        $data = $model->getData();
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
     * @param AbstractModel $model
     * @return \Vstore\Router\Model\AbstractModel|null
     */
    public function get(AbstractModel $model): ?AbstractModel
    {
        if ($model->getData('id')) {
            return $this->getById($model->getData('id'));
        }

        return null;
    }

    /**
     * @param AbstractModel $model
     * @return bool
     */
    public function delete(AbstractModel $model): bool
    {
        if ($model->getId('id')) {
            return $this->deleteById($model->getData('id'));
        }
        $model->deleteData();

        return true;
    }

    /**
     * @param mixed $id
     * @return bool
     */
    public function deleteById(mixed $id) : bool
    {
        $this->startTransaction();

        try {
            $this->getConnect()
                ->query(
                    "DELETE FROM " . $this->getInstance()::TABLE_NAME . " WHERE " . $this->getInstance()::PRIMARY_KEY . "=" . $id
                );

        } catch (\Exception $e) {
            $this->rollbackTransaction();

            return false;
        }
        $this->endTransaction();

        return true;
    }

    /**
     * @param mixed $id
     * @return AbstractModel
     */
    public function getById(mixed $id)
    {
        $model = new $this->instance();

        try {
            $data = $this->getConnect()
                ->query("SELECT * FROM " . $this->getInstance()::TABLE_NAME . " WHERE " . $this->getInstance()::PRIMARY_KEY . "=" . $id)
                ->fetchAll();
            $model->setData(array_shift($data));
        } catch (\Exception $e) {
        }

        return $model;
    }

    /**
     * @return void
     */
    protected function startTransaction(): void
    {
        $this->getConnect()->beginTransaction();
    }

    /**
     * @return void
     */
    protected function endTransaction(): void
    {
        $this->getConnect()->commit();
    }

    /**
     * @return void
     */
    protected function rollbackTransaction(): void
    {
        $this->getConnect()->rollBack();
    }
}
