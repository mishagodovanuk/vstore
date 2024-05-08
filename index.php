<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require 'vendor/autoload.php';
require 'etc/config.php';

use Vstore\Router\Router;

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

$router->get('/account/login', 'Account@loginAction')->setPermission('admin.view');
$router->post('/account/login', 'Account@loginPostAction')->setPermission('admin.update');

$router->get('/account/register', 'Account@registerAction');
$router->post('/account/register', 'Account@registerPostAction');

$router->any('/account/logout', 'Account@logoutAction');

$router->get('/vehicle/list', 'Vehicle@listAction');
$router->get('/vehicle/:id', 'Vehicle@viewAction');
$router->get('/vehicle/edit/:id', 'Vehicle@editAction');
$router->get('/vehicle/create', 'Vehicle@createAction');


$router->post('/vehicle/save', 'Vehicle@saveAction');
$router->post('/vehicle/save/:id', 'Vehicle@updateAction'); //used this one because html form not recognized put

$router->delete('/vehicle/delete/:id', 'Vehicle@deleteAction');

$router->run();