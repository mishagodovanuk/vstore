<?php

declare(strict_types=1);

namespace Vstore\Router\Model;

/**
 * Class AbstractModel
 */
abstract class AbstractModel
{
    /**
     * Used to store the table name
     *
     * @var string
     */
    public const TABLE_NAME = '';

    /**
     * Used to store the primary key
     */
    public const PRIMARY_KEY = '';

    /**
     * @return string
     */
    public function getTable(): string
    {
        return static::TABLE_NAME;
    }

    /**
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return static::PRIMARY_KEY;
    }

    /**
     * @param string|null $key
     * @return array|mixed|null
     */
    public function getData(string|null $key = null): mixed
    {
        $classProps = get_class_vars(__CLASS__);
        $instanceProps = get_object_vars($this);
        $data = array_merge($classProps, $instanceProps);

        if ($key !== null) {
            if (!array_key_exists($key, $data)) {
                $data[$key] = null;
            }

            return $data[$key];
        }

        return $data;
    }

    /**
     * @param array|string $keyOrArray
     * @param mixed $value
     * @return $this
     */
    public function setData(array|string $keyOrArray, mixed $value = null): static
    {
        if (is_array($keyOrArray) && !empty($keyOrArray)) {
            foreach ($keyOrArray as $key => $val) {
                if (property_exists($this, $key)) {
                    $this->$key = $val;
                }
            }
        } else {
            if ($keyOrArray && property_exists($this, $keyOrArray)) {
                $this->$keyOrArray = $value;
            }
        }

        return $this;
    }

    /**
     * @param string|null $key
     * @return $this
     */
    public function deleteData(string|null $key = null): static
    {
        if ($key === null) {
            foreach (get_object_vars($this) as $prop => $val) {
                $this->$prop = null;
            }
        } else {
            if (property_exists($this, $key)) {
                unset($this->$key);
            }
        }

        return $this;
    }
}
