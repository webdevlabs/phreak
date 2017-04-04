<?php
return [
    Middleware::basePath(BASE_PATH),
    Middleware::trailingSlash(),
    Middleware::responseTime(),
    new Plugins\Middlewares\LanguageDetect($language),
    new Plugins\Middlewares\Phroute($dispatcher)
];