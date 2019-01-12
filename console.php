<?php

$consoleRouteName = 'console';

if (@$_SERVER['argv'][0]) {
    if (@$_SERVER['argv'][1]) {
        $_SERVER['argv'][2] = $_SERVER['argv'][1];
        $_SERVER['argv'][1] = $consoleRouteName;
        $_SERVER["REQUEST_URI"] = $consoleRouteName.'/'.$_SERVER['argv'][2];
    }else {
        $_SERVER['argv'][1] = $consoleRouteName;
        $_SERVER["REQUEST_URI"] = $consoleRouteName;
    }
}

require_once __DIR__.'/public/index.php';
