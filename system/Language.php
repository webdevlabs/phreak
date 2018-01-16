<?php
namespace System;

class Language {
    public $current, $default;    
    public $available_languages = ['en', 'it', 'de', 'fr','bg'];

    public function __construct (Config $conf) {
        $this->conf = $conf;
        $this->default = 'en';
        //$this->current = $this->default; // moved to middleware
    }
    
}
