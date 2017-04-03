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

error_reporting(-1);

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

/**
 * Load Middlewares
 */
//Create a relay dispatcher and add some middlewares:
use Psr7Middlewares\Middleware;
use Psr7Middlewares\Middleware\LanguageNegotiator;
$relay = new Relay\RelayBuilder();
$dispatcher = $relay->newInstance([
    Middleware::responseTime(),
    Middleware::LanguageDetect($language->available_languages),
    new Plugins\Middlewares\Phroute($dispatcher)    
]);
$response = $dispatcher($request, $response);
$requestURI=$request->getUri()->getPath();
//echo '<br/>'.$request->getUri()->getPath().'<br/>';

//$selectedlang=LanguageNegotiator::getLanguage($request);
//echo $selectedlang;
//$language->load($selectedlang);


/**
 * Load System Language 

 
// Create the requestURI value and exclude subdir
//$requestURIarray=parse_url($_SERVER['REQUEST_URI']);
//$requestURI=after(BASE_PATH,$requestURIarray['path']);
//$requestMethod=$_SERVER['REQUEST_METHOD'];
$requestURI=after(BASE_PATH,$request->getUri()->getPath());

// Detect requested language and rewrite requestURI
$language=$container->get('System\Language');
// split the URL in parts
$URIparts = explode("/", $requestURI);
// check if language is set by url
if (in_array($URIparts[1], $language->available_languages)) {
    // rewrite requestURI
    $requestURI=after($URIparts[1],$requestURI);
    $language->load($URIparts[1]);
}else {
    $language->load('default');
}
*/

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
 $session->set('requestURI',$requestURI);

 /**
  * Load System Modules
  */
$modules = $container->get('\System\Modules');
$modules->loadRoutes($router);

// Cache the routes data
$item = $cache->getItem('req_'.$requestURI);
$routesData = $item->get();
if ($item->isMiss()) {
	$item->lock();    
    $routesData=$router->getData();
    $item->set($routesData);
	$cache->save($item);
}

// Use custom router resolver with dependency injection 
$resolver = new System\RouterResolver($container);

// Create Route Dispatcher object
$dispatcher = new Phroute\Phroute\Dispatcher($routesData, $resolver);

// Create the Response Emitter
$emitter =  new Zend\Diactoros\Response\SapiEmitter;
