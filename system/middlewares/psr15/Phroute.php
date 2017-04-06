<?php
namespace System\Middlewares\PSR15;

use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use \Phroute\Phroute\Dispatcher;
use \Phroute\Phroute\Exception\HttpRouteNotFoundException;
use \Phroute\Phroute\Exception\BadRouteException;

class Phroute
{

    /**
     * @var Dispatcher Phroute dispatcher
     */
    private $router;

    /**
     * Set the Dispatcher instance.
     *
     * @param Dispatcher|null $router
     */
    public function __construct(Dispatcher $router)
    {
        $this->router = $router;
    }

    /**
     * Process a server request and return a response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $response = $delegate->process($request);        
        // Application is loaded. Execute Route Dispatcher
        // WITH PSR15 REQUEST/RESPONSE HANDLERS
        try {
            $_SESSION['requestURI']=$request->getUri()->getPath();
            $response = $this->router->dispatch($request->getMethod(), $request->getUri()->getPath());
            $response = $response->withStatus(200);
        }
        catch (HttpRouteNotFoundException $e) {
                return $response->withStatus(404);
        }
        catch (BadRouteException $e) {
                return $response->withStatus(405);
        }
        return $response;
    }
}
