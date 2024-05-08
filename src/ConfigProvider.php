<?php

declare(strict_types=1);

namespace Vstore\Router;

/**
 * Class ConfigProvider
 */
class ConfigProvider
{
    /**
     * @var array
     */
    protected array $config = [];

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
     * @return string|null
     */
    public function getBasePath(): ?string
    {
        return $this->config['base_path'] ?? null;
    }

    /**
     * @return array|null
     */
    public function getDatabaseConfig(): ?array
    {
        return $this->config['db'] ?? null;
    }
}
