<?php

namespace Vstore\Router\Model;

use Vstore\Router\Model\AbstractModel;
use Vstore\Router\Model\AbstractConnect;

/**
 *
 */
abstract class AbstractRepository extends AbstractConnect
{
    /**
     * @var
     */
    public $instance;

    /**
     * @param $instance
     * @return void
     */
    protected function setInstance($instance)
    {
        $this->instance = $instance;
    }

    /**
     * @return mixed
     */
    protected function getInstance()
    {
        return $this->instance;
    }

    /**
     * @param $model
     * @return \Vstore\Router\Model\AbstractModel|false
     */
    public function save($model): AbstractModel | false
    {
        $data = $model->getData();
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
     * @param $model
     * @return void
     */
    protected function update($model)
    {
        $this->startTransaction();

        //TODO implement update method

        $this->endTransaction();
    }

    /**
     * @param $model
     * @return \Vstore\Router\Model\AbstractModel|null
     */
    public function get($model): AbstractModel | null
    {
        if ($model->getData('id')) {
            return $this->getById($model->getData('id'));
        }

        return null;
    }

    /**
     * @param $model
     * @return bool
     */
    public function delete($model) : bool
    {
        if ($model->getId('id')) {
            return $this->deleteById($model->getData('id'));
        }

        $model->deleteData();

        return true;
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteById($id) : bool
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
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        try {
            $data = $this->getConnect()
                ->query("SELECT * FROM " . $this->getInstance()::TABLE_NAME . " WHERE " . $this->getInstance()::PRIMARY_KEY . "=" . $id)
                ->fetchAll();

            $model = new $this->instance();
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
