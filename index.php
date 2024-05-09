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

$router->get('/', 'Dashboard@indexAction')
    ->setPermission('admin.view')
    ->setPermission('user.view');
$router->get('/companys', 'Dashboard@companyAction')
    ->setPermission('admin.view')
    ->setPermission('user.view');
$router->get('/drivers', 'Dashboard@driversAction')
    ->setPermission('admin.view')
    ->setPermission('user.view');
$router->get('/warehouses', 'Dashboard@warehouseAction')
    ->setPermission('admin.view')
    ->setPermission('user.view');
$router->get('/products', 'Dashboard@productAction')
    ->setPermission('admin.view')
    ->setPermission('user.view');
$router->get('/admin', 'Dashboard@adminAction')
    ->setPermission('admin.view');

$router->get('/account/login', 'Account@loginAction')
    ->setPermission('guest.view');
$router->post('/account/login', 'Account@loginPostAction')
    ->setPermission('guest.update');

$router->get('/account/register', 'Account@registerAction')
    ->setPermission('guest.view');
$router->post('/account/register', 'Account@registerPostAction')
    ->setPermission('guest.update');

$router->any('/account/logout', 'Account@logoutAction');

$router->get('/vehicle/list', 'Vehicle@listAction')
    ->setPermission('admin.view')
    ->setPermission('user.view');
$router->get('/vehicle/:id', 'Vehicle@viewAction')
    ->setPermission('admin.view')
    ->setPermission('user.view');
$router->get('/vehicle/edit/:id', 'Vehicle@editAction')
    ->setPermission('admin.view|update');
$router->get('/vehicle/create', 'Vehicle@createAction')
    ->setPermission('admin.view|update');

$router->post('/vehicle/save', 'Vehicle@saveAction')
    ->setPermission('admin.create');
$router->post('/vehicle/save/:id', 'Vehicle@updateAction')
    ->setPermission('admin.update');

$router->delete('/vehicle/delete/:id', 'Vehicle@deleteAction')
    ->setPermission('admin.delete');

$router->run();