<?php

namespace Vstore\Router\Model;

abstract class AbstractModel
{
    public const TABLE_NAME = '';

    public const PRIMARY_KEY = '';

    public function getTable(): string
    {
        return static::TABLE_NAME;
    }

    public function getPrimaryKey(): string
    {
        return static::PRIMARY_KEY;
    }

    public function getData($key = null)
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

    public function setData($keyOrArray, $value = null)
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

    public function deleteData($key = null)
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
