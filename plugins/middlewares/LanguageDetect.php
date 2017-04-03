<?php

namespace Plugins\Middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use \System\Language;

/**
 * Middleware to calculate the response time duration.
 */
class LanguageDetect
{
    private $language;

    /**
     * Define de available languages.
     *
     * @param array $languages
     */
    public function __construct(Language $language)
    {
        $this->language = $language;
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
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $language = null;
        $uri = $request->getUri();

            $path = ltrim($uri->getPath(), '/');
            $dirs = explode('/', $path, 2);
            $first = strtolower(array_shift($dirs));

            if (!empty($first) && in_array($first, $this->language->available_languages, true)) {
                $language = $first;
                //remove the language in the path
                $request = $request->withUri($uri->withPath('/'.array_shift($dirs)));
                $this->language->current = $language;
            }
        return $next($request, $response);
    }
}
