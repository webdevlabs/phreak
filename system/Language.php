<?php
namespace System;

class Language {
    
    public $available_languages = array('en', 'it', 'de', 'fr','bg');

    public function __construct (Config $conf, Template $template) {
        $this->conf = $conf;
        $this->template = $template;
    }
    
    public function load ($lang='default') {
        if ($lang=='default') { $lang='en'; }
        $this->template->assign('language',$lang);
    }

}