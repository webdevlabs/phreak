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
require_once __DIR__.'/../bootstrap.php';


// Application is loaded. Execute Route Dispatcher
$use_psr=true;
if ($use_psr) {
// WITH PSR7 REQUEST/RESPONSE HANDLERS
try {
    ob_start();
    $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());
    $bufferedBody = ob_get_clean();
    $response->getBody()->write($bufferedBody);
    $response = $response->withStatus(200);
    return $emitter->emit($response);
    }
catch (Phroute\Phroute\Exception\HttpRouteNotFoundException $e) {
        $reponse = new Zend\Diactoros\Response\HtmlResponse($e->getMessage(), 404);
        return $emitter->emit($reponse);
}
catch (Phroute\Phroute\Exception\BadRouteException $e) {
      $allowedMethods = $routeInfo[1];
        $reponse = new Zend\Diactoros\Response\HtmlResponse($e->getMessage(), 405);
        return $emitter->emit($reponse);
}
}else {
// WITHOUT PSR7 REQUEST/RESPONSE HANDLERS
try {
    $response = $dispatcher->dispatch($requestMethod, $requestURI);
    $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());
}
catch (Phroute\Phroute\Exception\HttpRouteNotFoundException $e) {
        echo 'Error: ',  $e->getMessage(), "\n";
}
catch (Phroute\Phroute\Exception\BadRouteException $e) {
        echo 'Error: ',  $e->getMessage(), "\n";
}
}
