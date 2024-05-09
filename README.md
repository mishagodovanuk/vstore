
# Vstore (simple mvc system)

Simple php-mvc structure which support routing, middlewares, access roles, database active reccord, view proccessor

### Features
- Request methods
- Path and namespaces
- Dynamic router pattern
- Before After Middlewares
- PHP DI container
- Permission roles for routing
- Controller classes
- Request/Responce handling via Symphony http request
- Sessions via Symphony
- DB connect
- Db Active reccord pattern
- Model, Perository, Collection (in-progress)
- Layout proccessor for view files


More features comming soon...



## Basic Usage

For basic usage just implement Router class to the your entry point file like "index.php"

Example:
```php
<?php

require 'vendor/autoload.php';

use Vstore\Router\Router;

$router = new Router();

$router->get('/', function($data = null) {
    return 'Hello world';
});

$router->run();

```
and modify your .htaccess file to redirect all request to entry point
```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]
```


This is example of simple usage of routing with get param
## Routing

### Base Routing
For simple routing you can use this structure

```php
<?php

require 'vendor/autoload.php';

use Vstore\Router\Router;

$router = new Router();

$router->get('/', function($data = null) {
    return 'Hello world';
}); // to register get request 

$router->controller('/users', 'UserController'); // To auto discover all methods

$router->run();
```
### Path & Namespaces & middlewares
You can also determinate path and namespaces for controller or middlewares
By default it like that:

 ```php
[
  'paths' => [
    'controller' => 'Controller',
    'middleware' => 'Middleware',
  ],
  'namespaces' => [
    'controller' => '',
    'middleware' => '',
  ]
]
 ```
where path determinate folder for Controller class or Middleware class and namespaces determinate class namespaces

To set this config file just put this config array into Router::class contructor like that:
```php
$router = new \Vstore\Router\Router([
  'paths' => [
    'controllers' => 'Controllers',
    'middlewares' => 'Middlewares',
  ],
  'namespaces' => [
    'controllers' => 'Controllers',
    'middlewares' => 'Middlewares',
  ]
]);

$router->get('/', function() {
    return 'Hello World!';
});
```
Where paths (Controller and Middlewares) are the folders inside root directory
Also this array support:
- base_folder - by default ''
- main_method - used for entrypoint in class
- debug (bool) - debug mode

### Request methods
Current router support differrent HTTP request methods like:

- ANY       - All request methods
- GET       - Get method
- POST      - Post method
- PUT       - Put method
- DELETE    - Delete method
- HEAD      - Head method
- OPTIONS   - Options method
- PATCH     - Patch method
- XGET      - Request must be XmlHttpRequest
- XPOST     - Request must be XmlHttpRequest and Post method
- XPUT      - Request must be XmlHttpRequest and Put method
- XDELETE   - Request must be XmlHttpRequest and Delete method
- XPATCH    - Request must be XmlHttpRequest and Patch method

GET request:
Names for url in this example is custom you can set any if you whant

```php
$router->get('/get', function() {
  return 'Hello World.';
});
```
POST request:

```php
$router->post('/post', function(Request $request, Response $response) {
  return $response->setContent($request->getMethod());
});
```
PUT request:

```php
$router->put('/put', function(Request $request, Response $response) {
  return $response->setContent($request->getMethod());
});
```
DELETE request:

```php
$router->delete('/delete', function(Request $request, Response $response) {
  return $response->setContent($request->getMethod());
});
```
Xpost Request:

```php
$router->xpost('/xpost', function() {
  return 'Hello World.';
});
```
All types of request:


```php
$router->any('/all', function() {
  return 'Hello World.';
});
```
Combined requests:
to combine reqeust types can can use add function where first param is requests string where each request devided by '|'

```php
$router->add('GET|POST|DELETE', '/combined', function() {
  return 'Combined requests.';
});
```
Request params:
aslo you can define request params for your route:
```php
$router->get('/get', function() {
  return 'Hello World.';
}, ['name' => 'get request']);

$router->post('/post', function($id) {
  return "You passed {$id}";
});
```
### Parameters patterns

- :all - All characters
- :any - All chars without '/' char
- :id - Natural numbers (0, 1, 17, 167, 1881, 20956...)
- :number - Real numbers (1, -1.2, 5.5 ...)
- :float - Real numbers
- :bool - Boolean values. (true, false, 1, 0)
- :string - Alphanumeric characters
- :slug - URL format characters for SEO. (Alphanumeric characters, _ and -)
- :uuid - UUID
- :date - Y-m-d format date string

Paremeter:
```php
$router->get('/user/:string', function($value) {
  echo "Hello, {$value}";
});

# Output:
# /user/john ---> Hello, john
# /user/php ---> Hello, php
# /hello/ ---> Error!!!
```
Multiple parameters:
```php
$router->get('/post/:id/:slug', function($id, $slug) {
  echo "Post ID: {$id} <br />";
  echo "Post Slug: {$slug}";
});

# Output:
# /post/5/hello-world ---> Post ID: 5 <br />Post Slug: hello-world
# /post/17/php-router ---> Post ID: 17 <br />Post Slug: php-router
# /post/foo/bar ---> Error!!! (:id param must be digits.)
```
Optional parameters:
```php
$router->get('/page/:slug?', function($page = 'home') {
  return "Page: {$page}";
});

# Output:
# /page/contact ---> Page: contact
# /page/about-us ---> Page: about-us
# /page ---> Page: home
```
Custom patterns:
```php
$router->pattern(':lowercase', '[a-z]+');

$router->get('/test/:lowercase', function($value) {
  return "Value: {$value}";
});

# Output:
# /test/john ---> Value: john 
# /test/php ---> Value: php
# /test/Router ---> Error!!!
```
and for Several pattens:
```php
$patterns = [':lowcase' => '[a-z]+', ':upcase' => '[A-Z]+'];

$router->pattern($patterns);
```
### Controller classes
You can also use class as your endpoint like this:
```php
$router = new \Vstore\Router\Router([
  'paths' => [
      'controllers' => 'Controllers',
  ],
  'namespaces' => [
      'controllers' => 'Controllers',
  ],
]);

$router->get('/', 'IndexController@main');
# OR
# $router->get('/', ['IndexController', 'main']);
# OR
# $router->get('/', [IndexController::class, 'main']);

// other examples...
$router->get('/create', 'IndexController@create');
$router->post('/store', 'IndexController@store');
$router->get('/edit/:id', 'IndexController@edit');
$router->put('/update/:id', 'IndexController@update');
$router->delete('/delete/:id', 'IndexController@delete');
```
Here your Controller class must be locaded in root/Controller directory and nust have namespace Controller;
Also this controller MUST be extended from Vstore/Router/Http/Controller::class and implement parent::__construct function()

This is abstract Controller which already contains Symphony Request/Responce objects


If ther is some dirrectory inside the Controllers pattern must be like that:
```php
$router->get('/create', 'Backend.IndexController@create');
$router->post('/store', 'Backend.IndexController@store');

# OR 

$router->get('/create', 'Backend\\IndexController@create');
$router->post('/store', 'Backend\\IndexController@store');

# OR

$router->get('/create', [Backend\IndexController::class, 'create']);
$router->post('/store', [Backend\IndexController::class, 'store']);
```
Default method for controller is execute if you dont set method by:
```php
$router->post('/store', 'Backend.IndexController@store');
$router->post('/store', [Backend\IndexController::class, 'store']);
```
we used @ as delimiter for class -> function

__INVOKE method

You can also use __INVOKE method as controller class entrypoint
```php
namespace Controllers;

use Vstore\Router\Http\Controller;

class FooController extends Controller
{
    public function __construct(Request $request, Responce $responce) 
    {
        parent::__construct($request, $responce);
    }

    public function __invoke()
    {
        return 'Hello from Invoke!';
    }
}
$router->get('/foo', 'FooController');
$router->get('/foo', FooController::class);
```
### Auto generated routes
If you dont wanna create controller entrypoint function by yourself, you can just autogenerate it by using controller() method
```php
# Controllers/IndexController.php
namespace Controllers;

use Vstore\Router\Http\Controller;

class IndexController extends Controller
{
  public function main()
  {
    return 'Main method';
  }

  public function foo()
  {
    return 'Foo method';
  }

  public function bar($id)
  {
    return "Bar method {$id}";
  }

  public function baz(int $id, $name = 'PHP-Router')
  {
    return "Baz method {$id} - {$name}";
  }

  public function hello(int $id, string $name)
  {
    return "Hello method {$id} - {$name}";
  }
}

$router->controller('/', 'IndexController');
# OR
$router->controller('/', IndexController::class);
```
All routes will be generated together method name and parent path that you defined in controller method and their required and not required parameters. For the example at above, following routes will be generated:
```php
/
/foo
/bar/:any
/baz/:id/:any?
/hello/:id/:slug
```
Also, you can define method type via method name. It's simple! For example:
```php
use Vstore\Router\Http\Controller;

class IndexController extends Controller
{
  public function main()
  {
    return 'Main method';
  }

  public function getFoo()
  {
    return 'Foo method';
  }

  public function postBar()
  {
    return 'Bar route can only be worked POST method.';
  }
}
```
You can use all HTTP method type in PHP-Router like this while you define a new route method.

Also, If you use method name as camelCase, it will be convert snake-case in Route path automatically. For example:
```php
use Vstore\Router\Http\Controller;

class IndexController extends Controller
{$router->group('admin', function($router) {
    $router->get('foo', function() {
        return 'page: admin/foo';
    });

    $router->post('bar', function() {
        return 'page: admin/bar';
    });
});

# Created Routes:
/*
- GET /admin/foo
- POST /admin/bar
*/
  // other methods

  public function getHelloWorld()
  {
    return 'hello-world route';
  }
}

# Route path will be like this for the method: /hello-world
```
### Routes Grouping
You can also group routers
```php
$router->group('admin', function($router) {
    $router->get('foo', function() {
        return 'page: admin/foo';
    });

    $router->post('bar', function() {
        return 'page: admin/bar';
    });
});

# Created Routes:
/*
- GET /admin/foo
- POST /admin/bar
*/
```
### Routes Permissions
You can use permissions for each router by using setPermission()

```php
$router->get('/', 'Index@indexAction')
    ->setPermission('admin.view'); 
```
here pattern is role.permission

Multiple permissions:
For multiple permissions you can use '|' as permision delimiter

```php
$router->get('/', 'Dashboard@indexAction')
    ->setPermission('admin.create|view|delete|update');
```

Multiple roles:
For multople roles you can use setPermission() function twice or even more (currently this part of functionality is developing to make set permissions easyer)
```php
    $router->get('/', 'Dashboard@indexAction')
    ->setPermission('admin.view')
    ->setPermission('user.view');
```

Get permissions inside the Middleware or Controller
To get permission inside middlweare or router you can pass
```php
Vstore\Router\RouterPermission::class
```
which alredy caontain permission (this one is incapsulated by DI container)

Permission class contain:
- setPermission() - which is used for set permission
- getPermission($routeId) - which is get current route permission
- getAllPermission() - return all permissions

$routeId is passed by DI container and if you wanna egt it you can simple use container Interface like that:
```php
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vstore\Router\Http\Middleware;
use Vstore\Router\RouterPermission;

class AccessRoleMiddleware extends Middleware
{
    public function __construct(
        Request $request,
        Response $response,
        RouterPermission $routerPermission,
        ContainerInterface $container
    ) {
        parent::__construct($request, $response);
        $this->routerPermission = $routerPermission;
        //Used container to get current route id 
        $this->currentRouterId = $container->get('currentRouterId');
    }
```
and then used routerId to get permission.


## Middlewares
You can also use Middleware with PHP-Router. There are two ways to use Middleware; those are before method and after method.
### Before
Before method runs before route action does not trigger yet.

Request -> BEFORE MIDDLEWARE -> Action -> After Middleware -> Response
### After
After method runs after route action triggered.

Request -> Before Middleware -> Action -> AFTER MIDDLEWARE -> Response

### Basic usage
```php
# index.php file

$router = new \Vstore\Router\Router([
  'paths' => [
      'controllers' => 'Controllers',
      'middlewares' => 'Middlewares',
  ],
  'namespaces' => [
      'controllers' => 'Controllers',
      'middlewares' => 'Middlewares',
  ],
]);

$router->get('/', 'IndexController@main', ['before' => 'FooMiddleware']);
# OR 
$router->get('/', 'IndexController@main', ['before' => FooMiddleware::class]);
```

Take in mind that custom middleware classes like the custom controllers must be extended from basic \Vstore\Router\Http\Middleware::class and call parent::__construct()
```php
# Middlewares/FooMiddleware.php
namespace Middlewares;

use Vstore\Router\Http\Middleware;

class FooMiddleware extends Middleware
{
  public function __construct(Request $request, Responce $responce)
  {
      parent::__construct($request, $responce);
  }

  public function handle()
  {
    // your middleware codes

    return true;
  }
}
```
Your middleware class must have a method that called handle().

This method must return true value if your middleware is pass. If the method returns another value except true, this mean middleware is not valid. So, your request will be blocked for routes which using this middleware. Another example:
```php
# Middlewares/FooMiddleware.php
namespace Middlewares;

use Vstore\Router\Http\Middleware;
use Symfony\Component\HttpFoundation\Request;

class FooMiddleware extends Middleware
{
  public function handle(Request $request)
  {
    if (!$request->getSession()->get('auth')) {
      // you can redirect another url here 
      // or 
      // you can write error message, view, json response, etc...

      return false;
    }

    return true;
  }
}
```
You must definitely specify return. Besides, return value should be true to pass and valid the middleware. Otherwise, your request will be blocked by the middleware for current route which using the middleware as i mentioned above.

### Controller definition
You can define before and after middlewares in your Controllers. For example;
```php
use Vstore\Router\Http\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Responce;

class HomeController extends Controller
{
    /**
     * @var array Before Middlewares
     */
    public $middlewareBefore = [
        \FooMiddleware::class,
        'BarMiddleware',
    ];

    /**
     * @var array After Middlewares
     */
    public $middlewareAfter = [
        \BazMiddleware::class,
    ];

    public function __construct(Request $request, Responce $responce)
    {
        parent::__construct($request, $responce);
    }

    /**
     * @param Response $response
     */
    public function main()
    {
        return $this->response->setContent('Hello World');
    }
}
```
## No fount and errors
You can specify custom errors for Not Found and Exceptions Errors in PHP-Router.

For Not Found errors, you can use notFound method like this:
```php
$router->notFound(function(Request $request, Response $response) {
  // your codes.
}); 
```
For Exception errors, you can use error method like this:
```php
$router->error(function(Request $request, Response $response, Exception $exception) {
  // your codes.
});
```
You don't need to specify it. Because if you don't specify it, PHP-Router will be use default notFound and error behaviours.


## DI container
Currently this section is in development, we implement php-di/php-di simple container to enable autoinjection inside resolver classes

Inside RouterCommand::resolveClass
```php
 $builder = new ContainerBuilder();
        $builder->addDefinitions([
            RouterPermission::class => $this->permission,
            'currentRouterId' => (string)$routerId,
        ]);

        return $builder->build()->get($class);
```

here alos we implement some definition for dependencies
## Database and active reccord

For basic usage create config.php file inside your projectroot/etc/ folder
```php
# etc/config.php
<?php
$config = [
    'base_path' => '',
    'db' => [
        'host' => 'localhost',
        'username' => 'user',
        'password' => 'password',
        'database' => 'database',
    ]
];
```

This needed for config resolver class

Than you must Create a Repository and Model classes:
```php
projectRoot(Folder)
    Model(Folder)
        User.php
        UserRepository.php
```
### Model
Model is a object representing of database object

Init Rules:
- each model must be extended from Vstore\Router\Model\AbstractModel
- each model must cantain two consts (TABLE_NAME, PRIMATY_KEY)
```php
    public const TABLE_NAME = 'users';
    public const PRIMARY_KEY = 'id';
```
- each model must implement class valiable for each filed name excatly as it was named in database
- [optional] you can also implemet setter and getter functions to get or set data

By default each model realized getData() and setData() functions which is used for set and get data by get all as entire array by key => value
So our User Model will looks like that:
```php
<?php

declare(strict_types=1);

namespace Model;

use Vstore\Router\Model\AbstractModel;
use Model\Api\UserInterface;

class User extends AbstractModel implements UserInterface
{
    public const TABLE_NAME = 'users';

    public const PRIMARY_KEY = 'id';

    public string|int $id;

    public string $name;

    public string $email;

    public string $password;


    public string $role;


    public function getId(): int|string|null
    {
        return $this->getData('id');
    }

    public function getName(): ?string
    {
        return $this->getData('name');
    }

    public function getEmail(): ?string
    {
        return $this->getData('email');
    }

    public function getPassword(): ?string
    {
        return $this->getData('password');
    }

    public function setId(string $id): void
    {
        $this->setData('id', $id);
    }

    public function setName(string $name): void
    {
        $this->setData('name', $name);
    }

    public function setEmail(string $email): void
    {
        $this->setData('email', $email);
    }

    public function setPassword(string $password): void
    {
        $this->setData('password', $password);
    }

    public function setRole(string $role): void
    {
        $this->setData('role', $role);
    }

    public function getRole(): ?string
    {
        return $this->getData('role');
    }
}

```
Here i also implement Userinterface (not required) as part of client-contract

Basically from this Model you can see that we have database table:
```php
table: users
    id
    name
    email
    password
```

But as you can see we also have "role" variable which is not related to this table. Yes it's correct.
We used Model just for storing information, so we can implement any other fields which we want, in this case we used $role to store role information about user.

So using this Model you can simply organized your bussines logic
```php
# Example of simple loginPostAction

public function loginPostAction(): RedirectResponse
    {
        $email = $this->request->request->get('email');
        $password = $this->request->request->get('password');

        if (!$this->validator->isValidEmail($email)) {
            $this->session->getFlashBag()->add('error', 'Invalid email or password');

            return new RedirectResponse($this->request->headers->get('referer'));
        }

        $user = new User();
        $user->setData('email', $email);
        $user->setData('password', $password);

        if ($this->auth->authorize($user)) {
            return new RedirectResponse('/');
        }

        $this->session->getFlashBag()->add('error', 'Error during authorization, please try again.');

        return new RedirectResponse($this->request->headers->get('referer'));
    }
```
As you can see ewe create new User(), set up data from request and send model to authorization method

### Repository

In our case Repository used to:
- get
- save
- list
- delete

Reccord inside the database, i can call direct connection from Connection provider by using $this->getConnect()->query()

Init Rules:
- each Repository must be extended from Vstore\Router\Model\AbstractRepository
- each Repository must execute setInstance() inside __construct() method, which has Model::class string as parram.


There only two rules to init repository, Basically it already containt some basic methods like:
- save
- update
- get
- delete
- deleteById
- getById
- startTransaction
- rollbackTransaction
- endTransaction
- getConnect
- getInstance
- setInstance

But you can implement many more.

Example of UserReposiotry:
```php
<?php

declare(strict_types=1);

namespace Model;

use Model\Api\UserInterface;
use Model\Role;
use Vstore\Router\Model\AbstractRepository;
use Vstore\Router\Model\AbstractModel;
use Model\User;
use Model\Api\UserRepositoryInterface;
use Model\RoleRepository;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    protected RoleRepository $roleRepository;

    public function __construct(
        RoleRepository $roleRepository,
    ) {
        $this->roleRepository = $roleRepository;
        $this->setInstance(User::class);
    }

    public function getByEmail(string $email): UserInterface
    {
        $data = $this->getConnect()?->query("SELECT * FROM " . $this->getInstance()::TABLE_NAME . " WHERE email = '$email'")
            ->fetchAll();
        $model = new $this->instance();
        $model->setData(array_shift($data));

        if ($model->getId()) {
            $role = $this->roleRepository->getByUserId($model->getId());
            $model->setRole($role->getData('role'));
        }

        return $model;
    }

    public function getById(mixed $id): UserInterface
    {
        $data = $this->getConnect()->query("SELECT * FROM " . $this->getInstance()::TABLE_NAME . " WHERE id = '$id'")
            ->fetchAll();
        $model = new $this->instance();
        $model->setData(array_shift($data));

        if ($model->getData('id')) {
            $role = $this->roleRepository->getByUserId($id);
            $model->setRole($role->getData('role'));
        }

        return $model;
    }

    public function save(AbstractModel $model): AbstractModel|bool
    {
        $data = $model->getData();
        $role = 'user';

        if (array_key_exists('role', $data)) {
            $role = $data['role'];
            unset($data['role']);
        }
       
        $fields = implode(',', array_keys($data));
        $values = implode("','", array_values($data));

        $this->startTransaction();

        try {
            $this->getConnect()
                ->query("INSERT INTO " . $this->getInstance()::TABLE_NAME . " ($fields) VALUES ('$values')");
            $model->setData('id', $this->getConnect()->lastInsertId());

        } catch (\Exception $e) {
            $this->rollbackTransaction();

            return false;
        }

        $this->endTransaction();

        try {
            $roleModel = new Role();
            $roleModel->setData('user_id', $model->getData('id'));
            $roleModel->setData('role', $role);
            $this->roleRepository->save($roleModel);
        } catch (\Exception $e) {
        }

        return $model;
    }

    public function list(): array
    {
        $data = $this->getConnect()->query("SELECT * FROM " . $this->getInstance()::TABLE_NAME)
            ->fetchAll();
        $models = [];

        foreach ($data as $item) {
            $model = new $this->instance();
            $model->setData($item);
            $role = $this->roleRepository->getByUserId($model->getId());
            $model->setRole($role->getData('role'));
            $models[] = $model;
        }

        return $models;
    }
}
```

as you can see from some methods we just simple create new User (model) and than pass data from database to this object

## Layout processor
We can simply use layout files inside controller actions , currently allowed only phtml file.

### Basic usage
```php
namespace Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vstore\Router\Http\Controller;
use Vstore\Router\View\LayoutProccessor;

class Vehicle extends Controller
{
    protected LayoutProccessor $layoutProccessor;

    public function __construct(
        Response $response,
        LayoutProccessor $layoutProccessor,
        Request $request
    ) {
        parent::__construct($request, $response);

        $this->layoutProccessor = $layoutProccessor;
        $this->vehicleRepository = $vehicleRepository;
    }

    public function listAction(): Response
    {
    
        return $this->response->setContent(
            $this->layoutProccessor->render('vehicle/list')
        );
    }
```

As you can see as the responce we used layout processor and pass the filename to the render function. Proccessor takes main.php layout and incapsulate list.phtml inside it as after it reutrn result.

### Templates structure

```php
projectRoot (folder)
    view (folder) - its a base path for all view files 
        layouts (folder) - folder for layouts
        templates (folder) - folder for templates 
``` 

You can also create subdirectories inside the layouts and templates dirs to separate some views by some logic.

Entry folder for render function is templates so if you need to resolve some subdirs just pass it as string to render function all must be separated by '/'.

### Layout processr functionality

Render functoin ():
by default render functoin has three params
- string $template - required
- array $data - optional, used to set data to template
```php
return $this->response->setContent(
            $this->layoutProccessor
                    ->render('dashboard', ['user'=>'John Doe'])
        );
```
- string $template - optional, used to change layout, by default main.php

Also you can use other LayoutProcessor function:
- setTitle() - set page title
```php
return $this->response->setContent(
            $this->layoutProccessor
                ->setTitle('Dashboard')
                ->render('dashboard', ['user'=>'John Doe'])
        );
``` 
- setData() - alter function for pass data to template
```php
$items = $this->vehicleRepository->list();

        return $this->response->setContent(
            $this->layoutProccessor
                ->setTitle('Vehicle List')
                ->setData('items', $items)
                ->render('vehicle/list')
        );
```
As you can see here we pass $items by items key, also setData param can be array of data.

### Layout phtml file

Used to be main site theme, each layout must also execute <?= $this->displayBody()?> function to make template incapsulation

$layout already contain all template $data, you can simple get it by $this->getData('key')
