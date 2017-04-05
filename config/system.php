<?php
/**
 * SYSTEM CONFIG 
 */ 
return [
    'site_key'=>'put_your_encryption_key_here',
    'max_upload_size'=>'52428800', //filesize in bytes also depends on server settings inside php.ini (1MB=1048576bytes)
    'session_expire'=>'30', // session TTL (time to live) in minutes
    'websocket_server'=>'ws://127.0.0.1:12345',
    'cache_routes'=>true
];
