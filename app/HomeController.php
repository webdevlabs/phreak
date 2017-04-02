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
        $title="webdevlabs";

        $this->template->assign([
            'page_title'=>$title,
            'page_content'=>$msg
        ]);
        return $this->template->display('layout.tpl');
    }    

}