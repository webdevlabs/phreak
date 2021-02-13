<?php
/**
 * Phreak! - the ultimate development tool for lamez.
 *
 * ultralight fast PHP HMVC framework
 *
 * @author Simeon Lyubenov <lyubenov@gmail.com>
 *
 * @link http://www.lamez.org
 * @link https://www.webdevlabs.com
 */
if (version_compare(phpversion(), '5.6.0', '<')) {
    exit('PHP5.6+ Required! PHP7 Recommended.');
}

/*
 * Display errors (disable it on production)
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE); // Show all except notice

/**
 * Load BASE config first.
 */
require_once __DIR__.'/config/base.php';

/**
 * Load AutoLoaders.
 */
require_once __DIR__.'/system/functions.php';
require_once __DIR__.'/system/autoload.php';
require_once __DIR__.'/vendor/autoload.php';

/**
 * Load Dependency Injector Container (PHP-DI).
 */
$container = DI\ContainerBuilder::buildDevContainer();

/**
 * Load System Config.
 */
$config = $container->get('System\Config');

/**
 * Load System Cache Library (Stash).
 */
$cache = $container->get('System\Cache');

/**
 * Load System Logger.
 */
$logger = $container->get('System\Logger');

/*
 * Load Database
 * Create a new PDO connection to MySQL
 * Create a new static DB class object
 */
System\DB::$c = (new System\Database($container))->connect();

/**
 * Load System Language.
 */
$language = $container->get('System\Language');

/**
 * Load HTTP Request/Response libs.
 */
$response = new Zend\Diactoros\Response();
$response = $response
//    ->withHeader('Content-Type', 'text/html')
    ->withAddedHeader('X-Phreak-KEY', $config->system['site_key'])
    ->withHeader('Cache-Control', 'private, max-age=3600, must-revalidate');
$request = Zend\Diactoros\ServerRequestFactory::fromGlobals();
$requestURI = $request->getUri()->getPath();

/**
 * Load Router (Phroute).
 */
$router = new Phroute\Phroute\RouteCollector();

// Load System Routes Definitions
require_once ROOT_DIR.'/routes.php';

 /**
  * Load System Secure Session Handler.
  */
 $session = $container->get('System\Session');
 $session->start();
 $session->set('admin_id', 1);

/**
 * Load System Modules and Routes.
 */
$modules = $container->get('System\Modules');
$modules->loadRoutes($router);

/*
 * Cache Routes
 */
if ($config->system['cache_routes']) {
    $item = $cache->getItem('routes');
    $routesData = $item->get();
    if ($item->isMiss()) {
        $item->lock();
        $routesData = $router->getData();
        $item->set($routesData);
        $cache->save($item);
    }
} else {
    $routesData = $router->getData();
}

// Use custom router resolver with dependency injection container
$resolver = new System\RouterResolver($container);

// Create Route Dispatcher object
$dispatcher = new Phroute\Phroute\Dispatcher($routesData, $resolver);

/*
 * Load Middlewares
 */
use Psr7Middlewares\Middleware;

$relay = new Relay\RelayBuilder();

//Create a relay dispatcher and add some middlewares
$relaydispatcher = $relay->newInstance([
    Middleware::basePath(BASE_PATH),
    Middleware::trailingSlash(),
    Middleware::responseTime(),
    new System\Middlewares\LanguageDetect($language),
    new System\Middlewares\Phroute($dispatcher),
]);

$response = $relaydispatcher($request, $response);
