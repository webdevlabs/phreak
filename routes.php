<?php
/**
 * DEFAULT ROUTES
 */

// Add default homepage route
$router->get('/', ['App\HomeController','showIndex']);

/*
$router->get('/gz', function() use ($template) {
    $template->assign([
            'page_title'=>'gz',
            'page_content'=>'tar.gz'
        ]);
    $template->display('layout.tpl');
});
*/
