<?php
/**
 * CACHE CONFIG 
 */ 
return [
    'memcached'=>[
        'host'=>'127.0.0.1',
        'port'=>'11211'
    ],
    'redis'=>[
        'host'=>'127.0.0.1',
        'port'=>'6379'        
    ],
    'stash'=>[
        'cachedir'=>ROOT_DIR.'/storage/cache/stash'
    ]
];
