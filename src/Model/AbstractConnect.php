<?php

namespace Vstore\Router\Model;

use Vstore\Router\ConfigProvider;

/**
 *
 */
abstract class AbstractConnect
{
    /**
     * @var
     */
    protected $connect;

    /**
     * @return \PDO
     */
    public function getConnect()
    {
        if (!$this->connect) {
            $configProvider = new ConfigProvider();
            $db = $configProvider->getDatabaseConfig();
            $this->connect = new \PDO(
                "mysql:host={$db['host']};dbname={$db['database']}",
                $db['username'],
                $db['password']
            );
            $this->connect->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        }

        return $this->connect;
    }
}
