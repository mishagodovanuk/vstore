<h1>Simple router system</h1>
<h4>Functionality:</h4>
<ul>
  <li>Routing</li>
  <li>Middleware</li>
  <li>Request and Response</li>
  <li>Database Active records</li>
  <li>Layout processor</li>
</ul>

<h2>Routing</h2>
<p>index.php</p>
<code>
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

    $router->run();
</code>

<p>also required a htaacess file</p>
<code>
    RewriteEngine On<br>
    RewriteCond %{REQUEST_FILENAME} !-d<br>
    RewriteCond %{REQUEST_FILENAME} !-f<br>
    RewriteRule ^ index.php [L]<br>
</code>

You can set callback function for each router like that
<code>
# GET Request
    $router->get('/get-request', function() {
    return 'Hello World.';
    });
# POST Request
    $router->post('/post-request', function() {
    return 'Hello World.';
    });
</code>    

<h4>Available methods</h4>
<code>
    ANY => All request methods<br>
    GET => Get method<br>
    POST => Post method<br>
    PUT => Put method<br>
    DELETE => Delete method<br>
    HEAD => Head method<br>
    OPTIONS => Options method<br>
    PATCH => Patch method<br>
    XGET => Request must be XmlHttpRequest<br>
    XPOST => Request must be XmlHttpRequest and Post method<br>
    XPUT => Request must be XmlHttpRequest and Put method<br>
    XDELETE => Request must be XmlHttpRequest and Delete method<br>
    XPATCH => Request must be XmlHttpRequest and Patch method<br>
</code>