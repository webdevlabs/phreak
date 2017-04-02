<?php
namespace Modules\Admin\Models;
use System\Config;

class Auth {
    public function __construct (Config $conf) {
        $this->conf = $conf;
        $this->user = true;
    }
    
    public function checkSession () {
        if (!$this->user) {
            echo "Nope! Must be authenticated";
            return false;
        }        
    }
}