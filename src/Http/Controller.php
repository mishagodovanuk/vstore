<?php

namespace Vstore\Router\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Abstract Controller class.
 */
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

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var Response
     */
    protected Response $response;

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(
        Request $request,
        Response $response
    ) {
        $this->request = $request->createFromGlobals();
        $this->response = $response;
    }
}
