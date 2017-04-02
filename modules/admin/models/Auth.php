<?php
namespace Modules\Admin\Models;
use System\Config;

class Auth {
    public function __construct (Config $conf) {
        $this->conf = $conf;
        $this->admin = true;
    }
    
    public function checkSession () {
        if (!$this->admin) {
            echo "Nope! Must be authenticated";
            return false;
        }        
    }
}