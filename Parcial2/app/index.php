<?php

use Slim\Handlers\Strategies\RequestHandler;
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

//use Psr\Http\Message\ResponseInterface as Response;
//use Slim\Psr7\Response as ResponseMW;
//use Psr\Http\Message\ServerRequestInterface as Request;
//use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
//use Slim\Routing\RouteCollectorProxy;
//use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

//require_once './utils/AccesoDatos.php';
//require_once './middlewares/Logger.php';

//require_once './controllers/UsuarioController.php';
require_once __DIR__.'/utils/jwtController.php';

require_once __DIR__."/routers/index.router.php";

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
$app->group('', \indexRouter::class );

$app->run();
