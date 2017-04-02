<?php
define('SITE_KEY','put_your_encryption_key_here');

/* BASE SETTINGS */
//define('BASE_URL',"http://".$_SERVER['HTTP_HOST']."/cms");
define('BASE_PATH',"/phreak"); // leave empty if not in subdir
define('BASE_URL',"http://localhost:8080".BASE_PATH);
define('ROOT_DIR', dirname(__DIR__));

/* OTHERS */
// filesize in bytes also depends on server settings inside php.ini (1MB=1048576bytes)
define('MAX_UPLOAD_SIZE','52428800');
define('SESSION_EXPIRE','30'); // session TTL (time to live) in minutes
define('WEBSOCKET_SERVER','ws://127.0.0.1:12345');
