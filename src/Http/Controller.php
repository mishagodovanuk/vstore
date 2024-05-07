<?php

namespace Vstore\Router\Http;

abstract class Controller extends Http
{
    /**
     * @var array Before Middlewares
     */
    public array $middlewareBefore = [];

    /**
     * @var array After Middlewares
     */
    public array $middlewareAfter = [];
}