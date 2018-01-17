<?php
/* BASE SETTINGS */
define('BASE_PATH',""); // if running in subdir, leave empty if not or running with php's built-in webserver
define('BASE_URL',"http://".$_SERVER['HTTP_HOST'].BASE_PATH);
define('ROOT_DIR', dirname(__DIR__));
