<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Doctrine\DBAL\DriverManager;
use Laminas\Diactoros\ServerRequestFactory;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();



$connectionParams = [
    'dbname' => $_ENV['DB_NAME'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASS'],
    'host' => $_ENV['DB_HOST'],
    'port' => $_ENV['DB_PORT'],
    'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
];
$conn = DriverManager::getConnection($connectionParams);
$stmt = $conn->prepare('SELECT * FROM users');
$result = $stmt->executeQuery();
var_dump($result->fetchAllAssociative());



// Create a DI container
$container = require_once __DIR__ .'/../bootstrap.php';

// Load routes from the routes definition file.
$routes = require __DIR__ . '/../app/Routes/routes.php';

// Initialize FastRoute dispatcher.
$dispatcher = FastRoute\simpleDispatcher($routes);

// Fetch method and URI from the server.
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI.
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

// Dispatch the route.
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo '404 Not Found';
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo '405 Method Not Allowed';
        break;

    case FastRoute\Dispatcher::FOUND:
        
        $request = ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );

        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        [$controller, $method] = explode('@', $handler);
        $controller = $container->get("App\\Controllers\\$controller");

        if (method_exists($controller, $method)) {
            echo $controller->$method($request, $vars);
        } else {
            http_response_code(500);
            echo "Method $method not found in $controller";
        }
        break;
}
