<?php

namespace Vstore\Router\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Abstract Middleware class.
 */
abstract class Middleware extends Http
{
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
