<?php

namespace Vstore\Router;

/**
 *
 */
class ConfigProvider
{
    /**
     * @var
     */
    protected $config;

    /**
     * Construct inits the config array
     */
    public function __construct() {
        $configFilePath = $_SERVER['DOCUMENT_ROOT'] . '/etc/config.php';
        if (file_exists($configFilePath)) {
            require $configFilePath;
            $this->config['db'] = $config['db'];
        } else {
            throw new Exception('Config file not found.');
        }
    }

    /**
     * @return mixed|null
     */
    public function getBasePath() {
        return $this->config['base_path'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getDatabaseConfig() {
        return $this->config['db'] ?? null;
    }
}
