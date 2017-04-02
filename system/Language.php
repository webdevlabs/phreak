<?php
namespace System;

class Language {
    public $current;    
    public $available_languages = array('en', 'it', 'de', 'fr','bg');

    public function __construct (Config $conf) {
        $this->conf = $conf;
    }
    
    public function load ($lang='default') {
        if ($lang=='default') { $lang='en'; }
        $this->current = $lang;
    }

}