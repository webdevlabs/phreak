<?php

namespace System\Middlewares\PSR15;

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
     * Define the available languages.
     *
     * @param array $languages
     */
    public function __construct(Language $language)
    {
        $this->language = $language;
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
        $language = null;
        $uri = $request->getUri();
        $path = ltrim($uri->getPath(), '/');
        $dirs = explode('/', $path, 2);
        $first = strtolower(array_shift($dirs));

        if (!empty($first) && in_array($first, $this->language->available_languages, true)) {
            $language = $first;
            $this->language->current = $language;
            //remove the language from the path
            $request = $request->withUri($uri->withPath('/'.array_shift($dirs)));
        }
        return $response;
    }
}
