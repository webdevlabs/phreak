<?php

namespace System;

use DI\Container;

class Database
{
    public function __construct(Container $container)
    {
        $this->conf = $container->get('System\Config');
        $this->log = $container->get('System\Logger');
    }

    public function connect()
    {
        $loaddbfile = ROOT_DIR.DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'Database'.ucfirst($this->conf->database['mysql']['driver']).'.php';
        if (is_file($loaddbfile) && $this->conf->database['mysql']['driver']) {
            $loadclass = 'System\Database'.ucfirst($this->conf->database['mysql']['driver']);
            $loaddb = new $loadclass($this->conf);

            return $loaddb;
        }
    }
}
