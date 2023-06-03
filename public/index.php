<?php
require_once __DIR__ . '/../vendor/autoload.php';

if (
  php_sapi_name() !== 'cli' && // Environnement d'exécution != console
  preg_match('/\.(ico|png|jpg|jpeg|css|js|gif)$/', $_SERVER['REQUEST_URI'])
) {
  return false;
}

// Initialisation de certaines choses
use App\Controller\ContactController;
use App\Controller\IndexController;
use App\Controller\UserController;
use App\Routing\RouteNotFoundException;
use App\Routing\Router;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/../.env');

// Doctrine
$paths = [__DIR__ . '/../src/Entity'];
$isDevMode = $_ENV['APP_ENV'] === 'dev';

// the connection configuration
$dbParams = [
  'driver'   => 'pdo_mysql',
  'host'     => $_ENV['DB_HOST'],
  'port'     => $_ENV['DB_PORT'],
  'user'     => $_ENV['DB_USER'],
  'password' => $_ENV['DB_PASSWORD'],
  'dbname'   => $_ENV['DB_NAME'],
];

$config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);
$connection = DriverManager::getConnection($dbParams, $config);
$entityManager = new EntityManager($connection, $config);

// Twig
$loader = new FilesystemLoader(__DIR__ . '/../templates/');
$twig = new Environment($loader, [
  'debug' => $_ENV['APP_ENV'] === 'dev',
  'cache' => __DIR__ . '/../var/twig/',
]);

// Appeler un routeur pour lui transférer la requête
$router = new Router([
  Environment::class => $twig,
  EntityManager::class => $entityManager
]);
$router->addRoute(
  'homepage',
  '/',
  'GET',
  IndexController::class,
  'home'
);
$router->addRoute(
  'contact_page',
  '/contact',
  'GET',
  ContactController::class,
  'contact'
);
$router->addRoute(
  'user_create',
  '/user/create',
  'GET',
  UserController::class,
  'create'
);

if (php_sapi_name() === 'cli') {
  return;
}

try {
  $router->execute($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
} catch (RouteNotFoundException $ex) {
  http_response_code(404);
  echo "Page not found";
}
