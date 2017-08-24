<?php
// Filters
$router->filter('authadmin', ['Modules\Admin\Models\Auth','checkSession']);

// ADMIN SECTION
$router->group([
        'prefix'=>'api', 
        'before'=>'authadmin'
    ], 
    function ($router) {
        $router->controller('/', 'Modules\Api\Controllers\Manage');
});
