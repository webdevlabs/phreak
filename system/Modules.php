<?php
namespace System;

class Modules {
    public function __construct (Config $conf) {
        $this->conf = $conf;
    }
    
    public function loadRoutes ($router) {
        $modules = include ROOT_DIR.'/config/modules.php';
        foreach ($modules as $modname) {
            if (file_exists(ROOT_DIR.'/modules/'.$modname.'/routes.php')) {
                include ROOT_DIR.'/modules/'.$modname.'/routes.php';
            }
        }
    }
    
}
