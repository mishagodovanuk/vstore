<?php

namespace Vstore\Router;

use Exception;

class RouterException extends Exception
{
    /**
     * Create Exception Class.
     *
     * @param string $message
     *
     * @param int $statusCode
     *
     * @throws Exception
     */
    public function __construct(string $message, int $statusCode = 500)
    {
        parent::__construct($message, $statusCode);
    }
}
