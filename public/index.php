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
error_reporting(0);
require_once __DIR__.'/../bootstrap.php';

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

// Application is loaded. Execute Route Dispatcher
try {
    $response = $dispatcher->dispatch($requestMethod, $requestURI);
}
catch (Phroute\Phroute\Exception\HttpRouteNotFoundException $e) {
        echo 'Error: ',  $e->getMessage(), "\n";
}
catch (Phroute\Phroute\Exception\BadRouteException $e) {
        echo 'Error: ',  $e->getMessage(), "\n";
}
    
