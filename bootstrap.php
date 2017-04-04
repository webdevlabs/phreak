<?php
/**
 * Phreak! - the ultimate development tool for lamez
 * 
 * ultralight fast PHP HMVC framework
 * 
 * @package phreak
 * @author Simeon Lyubenov <lyubenov@gmail.com>
 * @link http://www.lamez.org
 * @link https://www.webdevlabs.com
 * 
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE); // Show all except notice


include __DIR__ . '/config/system.php';
include __DIR__ . '/config/cache.php';
include __DIR__ . '/config/database.php';
include __DIR__ . '/system/functions.php';
include __DIR__ . '/system/autoload.php';
include __DIR__ . '/vendor/autoload.php';

/**
 * Load Cache Library (Stash)
 */
$cachedriver = new Stash\Driver\FileSystem(['path'=>ROOT_DIR.'/storage/cache/stash']);
$cache = new Stash\Pool($cachedriver);

/**
 * Load Dependency Injector Container (PHP-DI)
 */
$container = DI\ContainerBuilder::buildDevContainer();

/**
 * Load System Language 
 */
$language=$container->get('System\Language');

/**
 * Load HTTP Request/Response libs
 */
$response = new Zend\Diactoros\Response();
$response = $response
    ->withHeader('Content-Type', 'text/html')
    ->withAddedHeader('X-Phreak-KEY', SITE_KEY)
    ->withHeader('Cache-Control', 'private, max-age=3600, must-revalidate');
$request = Zend\Diactoros\ServerRequestFactory::fromGlobals();

$requestURI=$request->getUri()->getPath();

/**
 * Load Router (Phroute)
 */
$router = new Phroute\Phroute\RouteCollector();

// Load System Routes Definitions
require_once ROOT_DIR.'/routes.php';

/**
 * Load System Secure Session Handler
 */
 $session = $container->get('\System\Session');
 $session->start();
 $session->set('admin_id',1);
// $session->set('requestURI',$requestURI);

 /**
  * Load System Modules
  */
$modules = $container->get('\System\Modules');
$modules->loadRoutes($router);

$route_caching=true;
if ($route_caching) {
    // Cache the routes data
    $item = $cache->getItem('routes');
    $routesData = $item->get();
    if ($item->isMiss()) {
        $item->lock();    
        $routesData=$router->getData();
        $item->set($routesData);
        $cache->save($item);
    }
}else {
    $routesData=$router->getData();
}

// Use custom router resolver with dependency injection 
$resolver = new System\RouterResolver($container);

// Create Route Dispatcher object
$dispatcher = new Phroute\Phroute\Dispatcher($routesData, $resolver);

/**
 * Load Middlewares
 */
//Create a relay dispatcher and add some middlewares:
use Psr7Middlewares\Middleware;
$relay = new Relay\RelayBuilder();

$relaydispatcher = $relay->newInstance([
    Middleware::basePath(BASE_PATH),
    Middleware::trailingSlash(),
    Middleware::responseTime(),
    new System\Middlewares\LanguageDetect($language),
    new System\Middlewares\Phroute($dispatcher)    
]);
$response = $relaydispatcher($request, $response);
