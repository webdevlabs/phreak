<?php
// Filters
$router->filter('authadmin', ['Modules\Admin\Models\Auth','checkSession']);

// ADMIN SECTION
$router->group([
        'prefix'=>'admin', 
        'before'=>'authadmin'
    ], 
    function ($router) {
        $router->get('/', ['Modules\Admin\Controllers\Dashboard','showIndex'])
               ->controller('/', 'Modules\Admin\Controllers\Dashboard');
});

/*
$router->group([
        'prefix'=>'admin', 
        'before'=>'authadmin'
    ], 
    function ($router) {
        $router->get('/', ['App\Admin\Controllers\Main','showIndex'])
               ->get('dashboard', ['App\Admin\Controllers\Main','showDashboard'])
               ->get('settings', ['App\Admin\Controllers\Main', 'showSettings'])
               ->get('modules', ['App\Admin\Controllers\Main', 'showModules'])
               ->get('languages', ['App\Admin\Controllers\Main', 'showLanguages'])
               ->get('widgets', ['App\Admin\Controllers\Main', 'showWidgets'])
               ->get('admins', ['App\Admin\Controllers\Main', 'showAdmins'])
               ->get('update', ['App\Admin\Controllers\Main', 'showUpdate'])
               ->get('url_aliases', ['App\Admin\Controllers\Main', 'showURLAliases']);
});
*/
