<?php

namespace Vstore\Test;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vstore\Router\Router;

class RouterTest extends TestCase
{
    protected $router;

    protected function setUp(): void
    {
        $this->router = new Router([
            'paths' => [
                'controllers' => 'Controller',
                'middlewares' => 'Middlewares',
            ],
            'namespaces' => [
                'controllers' => 'Controller',
                'middlewares' => 'Middlewares',
            ],
        ],
            Request::createFromGlobals()
        );
    }

//    public function testRoutes()
//    {
//        $routes = [
//            ['/', 'Dashboard@indexAction', ['admin.view', 'user.view']],
//            ['/companys', 'Dashboard@companyAction', ['admin.view', 'user.view']],
//            ['/drivers', 'Dashboard@driversAction', ['admin.view', 'user.view']],
//            ['/warehouses', 'Dashboard@warehouseAction', ['admin.view', 'user.view']],
//            ['/products', 'Dashboard@productAction', ['admin.view', 'user.view']],
//            ['/admin', 'Dashboard@adminAction', ['admin.view']],
//            ['/account/login', 'Account@loginAction', ['guest.view']],
//            ['/account/login', 'Account@loginPostAction', ['guest.update'], 'POST'],
//            ['/account/register', 'Account@registerAction', ['guest.view']],
//            ['/account/register', 'Account@registerPostAction', ['guest.update'], 'POST'],
//            ['/account/logout', 'Account@logoutAction'],
//            ['/account/user/token', 'Account@getUserTokenAction'],
//            ['/vehicle/list', 'Vehicle@listAction', ['admin.view', 'user.view']],
//            ['/vehicle/:id', 'Vehicle@viewAction', ['admin.view', 'user.view']],
//            ['/vehicle/edit/:id', 'Vehicle@editAction', ['admin.view', 'update']],
//            ['/vehicle/create', 'Vehicle@createAction', ['admin.view', 'update']],
//            ['/vehicle/save', 'Vehicle@saveAction', ['admin.create'], 'POST'],
//            ['/vehicle/save/:id', 'Vehicle@updateAction', ['admin.update'], 'POST'],
//            ['/vehicle/delete/:id', 'Vehicle@deleteAction', ['admin.delete'], 'DELETE'],
//        ];
//
//        foreach ($routes as $route) {
//            list($path, $controller, $permissions, $method) = $route + [null, null, [], 'GET'];
//
//            $request = Request::create($path, $method);
//            $response = new Response();
//
//            $this->expectOutputString('Handler for ' . $controller . ' called with method ' . $method);
//
//            $this->router->any($path, function () use ($controller, $method) {
//                echo 'Handler for ' . $controller . ' called with method ' . $method;
//            });
//
//            $this->router->run($request, $response);
//        }
//    }

    public function testNotFoundRoute()
    {
        $request = Request::create('/nonexistent-route', 'GET');
        $response = new Response();

        $this->expectOutputString('404 not found (page currently in dev)');

        $this->router->notFound(function () {
            echo '404 not found (page currently in dev)';
        });

        $this->router->run($request, $response);
    }
}