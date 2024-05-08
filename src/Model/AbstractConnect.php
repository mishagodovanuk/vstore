<?php

declare(strict_types=1);

namespace Vstore\Router\Model;

use Vstore\Router\ConfigProvider;

/**
 * Class AbstractConnect
 */
abstract class AbstractConnect
{

    /**
     * @var
     */
    protected $connect;

    /**
     * @return \PDO|null
     */
    public function getConnect(): \PDO|null
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
