<?php

// Add default homepage route
$router->get('/', ['App\HomeController','showIndex']);

/*
$template = $container->get('System\Template');
$router->get('/gz', function() use ($template) {
    $template->assign([
            'page_title'=>'gz',
            'page_content'=>'tar.gz'
        ]);
    $template->display('layout.tpl');
});
*/