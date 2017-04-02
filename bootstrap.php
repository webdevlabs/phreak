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
// Create the requestURI value and exclude subdir
$requestURIarray=parse_url($_SERVER['REQUEST_URI']);
$requestURI=after(BASE_PATH,$requestURIarray['path']);
$requestMethod=$_SERVER['REQUEST_METHOD'];

// Detect requested language and rewrite requestURI
$language=$container->get('System\Language');
// split the URL in parts
$URIparts = explode("/", $requestURI);
// check if language is set by url
if (in_array($URIparts[1], $language->available_languages)) {
    $language->load($URIparts[1]);
    // rewrite requestURI
    $requestURI=after($URIparts[1],$requestURI);
}else {
    $language->load('default');
}

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
 
 /**
  * Load System \System\Modules
  */
$modules = $container->get('\System\Modules');
$modules->loadRoutes($router);

