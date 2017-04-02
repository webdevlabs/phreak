<?php
namespace System;

class Config {
   	public static $conf;

    public function __construct () {
        Config::$conf = [
            'site_url'=>BASE_URL,
            'site_title'=>'Phreak! ultralight and lightning fast PHP framework'
        ];
    }

	public function __get($name) {
		if (@array_key_exists($name, Config::$conf)) {
			return Config::$conf[$name];
		}
	}

    public function build () {
        if (is_readable(ROOT_DIR.'/config/system.php')) {
            $conf['system']=include(ROOT_DIR.'/config/system.php');
        }
        if (is_readable(ROOT_DIR.'/config/database.php')) {
            $conf['database']=include(ROOT_DIR.'/config/database.php');
        }
        if (is_readable(ROOT_DIR.'/config/cache.php')) {
            $conf['cache']=include(ROOT_DIR.'/config/cache.php');
        }
//        Config::$conf = $conf;
    }

}