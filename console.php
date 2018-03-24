<?php

$_SERVER['SERVER_PORT'] = 80;
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_URI'] = $argv[1];

require_once __DIR__.'/public/index.php';

