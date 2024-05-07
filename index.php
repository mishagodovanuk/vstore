<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require 'vendor/autoload.php';
require 'etc/config.php';

use Vstore\Router\ConfigProvider;
use Vstore\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vstore\Router\Model\AbstractConnect;
use Symfony\Component\HttpFoundation\Session\Session;

$router = new Router([
    'paths' => [
        'controllers' => 'Controller',
        'middlewares' => 'Middlewares',
    ],
    'namespaces' => [
        'controllers' => 'Controller',
        'middlewares' => 'Middlewares',
    ],
]);

$router->get('/', 'Dashboard@indexAction');
$router->get('/companys', 'Dashboard@companyAction');
$router->get('/drivers', 'Dashboard@driversAction');
$router->get('/warehouses', 'Dashboard@warehouseAction');
$router->get('/products', 'Dashboard@productAction');
$router->get('/admin', 'Dashboard@adminAction');

$router->get('/account/login', 'Account@loginAction');
$router->post('/account/login', 'Account@loginPostAction');

$router->get('/account/register', 'Account@registerAction');
$router->post('/account/register', 'Account@registerPostAction');

$router->any('/account/logout', 'Account@logoutAction');

$router->get('/vehicle/list', 'Vehicle@listAction');
$router->get('/vehicle/create', 'Vehicle@createViewAction');
$router->post('/vehicle/create', 'Vehicle@createAction');

$router->get('/vehicle/view/:id', 'Vehicle@viewAction');
$router->get('/vehicle/:id', 'Vehicle@editViewAction');
$router->post('/vehicle/:id', 'Vehicle@updateAction');
$router->delete('/vehicle/:id', 'Vehicle@deleteAction');



$router->run();