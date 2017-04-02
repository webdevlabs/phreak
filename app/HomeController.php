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
        $this->template->assign('page_title',$msg);
        return $this->template->display('layout.tpl');
    }    

}