<?php
namespace Modules\Admin\Controllers;

use System\Template;

class Dashboard {
    private $template;
    public function __construct (Template $template) {
        $this->template = $template;
        $this->template->assign('page_title','Admin Panel');
    }

   public function anyIndex() {
        $msg='Welcome Admin!';
        $this->template->assign('page_content',$msg);
        $this->template->display('layout.tpl');
    }    

   public function getDashboard() {
        $msg='Welcome Admin! This is your dashboard';
        $this->template->assign('page_content',$msg);
        $this->template->display('layout.tpl');
    }    

   public function getVue() {
    $this->template->setTemplateDir(ROOT_DIR.'/modules/admin/views/');
    $this->template->left_delimiter = "{!"; 
    $this->template->right_delimiter = "!}";         
    $this->template->display('vue.tpl');
    }    
}
