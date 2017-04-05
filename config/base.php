<?php
/* BASE SETTINGS */
define('BASE_PATH',"/phreak"); // leave empty if not in subdir or "/phreak" for example subdir
//define('BASE_URL',"http://localhost:8080".BASE_PATH);
define('BASE_URL',"http://".$_SERVER['HTTP_HOST'].BASE_PATH);
define('ROOT_DIR', dirname(__DIR__));
