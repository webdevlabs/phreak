<?php
namespace Modules\Admin\Controllers;
use System\Template;
use System\Config;

class Dashboard {
    private $template;
    public function __construct (Template $template) {
        $this->template = $template;
    }

   public function showIndex() {
        $msg='Welcome Admin!';
        return $this->template->display($msg);
    }    

   public function anyIndex() {
        $msg='Welcome Admin! This is your dashboard';
        return $this->template->display($msg);
    }    

}