<?php
namespace System\Middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\Exception\HttpRouteNotFoundException;
use Phroute\Phroute\Exception\BadRouteException;

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
     * Execute the middleware.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next) {
        // Application is loaded. Execute Route Dispatcher
        // WITH PSR7 REQUEST/RESPONSE HANDLERS
        try {
            $_SESSION['requestURI']=$request->getUri()->getPath();
-            ob_start();
 -            $this->router->dispatch($request->getMethod(), $request->getUri()->getPath());
 -            $bufferedBody = ob_get_clean();
 -            $response->getBody()->write($bufferedBody);
              $response = $response->withStatus(200);
        }
        catch (HttpRouteNotFoundException $e) {
                return $response->withStatus(404);
//                $reponse = new \Zend\Diactoros\Response\HtmlResponse($e->getMessage(), 404);
        }
        catch (BadRouteException $e) {
                return $response->withStatus(405);
//                $reponse = new \Zend\Diactoros\Response\HtmlResponse($e->getMessage(), 405);
        }
        return $next($request, $response);
    }
}
