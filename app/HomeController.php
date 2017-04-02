<?php
namespace App;
use System\Template;
use System\Config;

class HomeController {
    private $template;
    public function __construct (Template $template) {
        $this->template = $template;
    }

   public function showIndex()
    {
        $msg='Welcome';
        return $this->template->display($msg);
    }    

}