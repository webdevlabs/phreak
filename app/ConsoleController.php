<?php

namespace App\Controllers;

use System\Console;

class ConsoleController
{
    private $console;

    public function __construct(Console $console)
    {
        $this->console = $console;
        $this->console->namespace = 'App\\Commands';
    }

    public function anyIndex($params = null)
    {
        if ($params) {
            $this->console->run($params);
        } else {
            echo 'welcome to phreak\'s console';
        }
    }
}
