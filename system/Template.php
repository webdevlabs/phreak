<?php
namespace System;

class Template {
    public function __construct (Config $conf) {
        $this->conf = $conf;
    }
    
    public function display ($content) {
        echo "<title>{$this->conf->site_title}</title>";
        echo $content;
    }
}