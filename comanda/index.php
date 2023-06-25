<?php
//
function GetUsers()
{
    include_once "users.php";
    $l = Usuario::LeerUsuarios();
    foreach($l as $u)
    {
        Usuario::MostrarUsuario($u);
    }
}

function PostUsers($request)
{
    include_once "users.php";

    $datos = json_decode(file_get_contents('php://input'));
    $u = new Usuario($datos->name,$datos->surname,$datos->mail,$datos->pass,$datos->type);
    return $u->id;
}

function GetProducts()
{
    include_once "products.php";
    $l = Producto::LeerProducto();
    foreach($l as $p)
    {
        Producto::MostrarProducto($p);
    }
}

function PostProducts($request)
{
    include_once "products.php";

    $datos = json_decode(file_get_contents('php://input'));
    $u = new Producto($datos->name,$datos->crafter,$datos->type,$datos->mType,$datos->mSize);
    return $u->id;
}

function GetTables()
{
    include_once "tables.php";
    $l = Mesa::LeerMesa();
    foreach($l as $t)
    {
        Mesa::MostrarMesa($t);
    }
}

function PostTables($request)
{
    include_once "tables.php";

    $datos = json_decode(file_get_contents('php://input'));
    $t = new Mesa($datos->posicion);
    return $t->id;
}

function GetOrders()
{
    include_once "orders.php";
    $l = Mesa::LeerMesa();
    foreach($l as $o)
    {
        Orden::MostrarOrden($o);
    }
}

function PostOrders($request)
{
    include_once "orders.php";

    $datos = json_decode(file_get_contents('php://input'));
    $t = new Orden($datos->idTable);
    $products = array();
    foreach($datos -> products as $idProduct => $cantProduct)
    {
        $products[$idProduct] = $cantProduct;
    }
    $t->CargarOden($products);
    $t->AltaOrden();

    return $t->id;
}

// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;


require __DIR__ . '/vendor/autoload.php';

//Agrega el .ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
$app->get('[/]', function (Request $request, Response $response) {  
    //StateMachineGET();
    //echo "entre al get";
     
    $payload = json_encode(array('method' => 'GET', 'msg' => "Bienvenido a SlimFramework 2023"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('[/]', function (Request $request, Response $response) {    
    $payload = json_encode(array('method' => 'POST', 'msg' => "Bienvenido a SlimFramework 2023"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/test', function (Request $request, Response $response) {    
    $payload = json_encode(array('method' => 'POST', 'msg' => "Bienvenido a SlimFramework 2023"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->put('/test', function (Request $request, Response $response) {    
    $payload = json_encode(array('method' => 'PUT', 'msg' => "Bienvenido a SlimFramework 2023"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/test', function (Request $request, Response $response) {    
    $payload = json_encode(array('method' => 'GET', 'msg' => "Bienvenido a SlimFramework 2023"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});



$app->post('/users', function (Request $request, Response $response) {    
    $id = PostUsers($request);
    $response->getBody()->write("DONE".$id);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/users', function (Request $request, Response $response) {    
    GetUsers();
    $response->getBody()->write("DONE");
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/products', function (Request $request, Response $response) {    
    $id = PostProducts($request);
    $response->getBody()->write("DONE".$id);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/products', function (Request $request, Response $response) {    
    GetProducts();
    $response->getBody()->write("DONE");
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/tables', function (Request $request, Response $response) {    
    $id = PostTables($request);
    $response->getBody()->write("DONE".$id);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/tables', function (Request $request, Response $response) {    
    GetTables();
    $response->getBody()->write("DONE");
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/orders', function (Request $request, Response $response) {    
    $id = PostOrders($request);
    $response->getBody()->write("DONE".$id);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/orders', function (Request $request, Response $response) {    
    GetOrders();
    $response->getBody()->write("DONE");
    return $response->withHeader('Content-Type', 'application/json');
});


$app->run();
