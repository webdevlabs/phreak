<?php
namespace System;

class Config {
   	public $conf;
    private $configfiles;

    public function __construct () {
        $this->configfiles = [
            'system'=>ROOT_DIR.'/config/system.php',
            'cache'=>ROOT_DIR.'/config/cache.php',
            'database'=>ROOT_DIR.'/config/database.php',
            'session'=>ROOT_DIR.'/config/session.php'
        ];
        $this->conf = [
            'site_url'=>BASE_URL,
            'site_title'=>'Phreak! ultralight and lightning fast PHP framework'
        ];
        $this->conf = $this->build($this->conf);
    }

	public function __get($name) {
		if (@array_key_exists($name, $this->conf)) {
			return $this->conf[$name];
		}
	}

    public function build ($conf) {
//        echo "Building conf...<br/>";
        foreach ($this->configfiles as $cfgkey => $cfgfile) {
            if (is_readable($cfgfile)) {
                $conf[$cfgkey]=include($cfgfile);
            }
        }
        return $conf;
    }

}
