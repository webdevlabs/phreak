<?php
namespace Modules\Admin\Controllers;

use System\Template;
use System\Cache;

class ClearCache {

    private $template;
    private $cache;

    public function __construct (Template $template, Cache $cache) {
        $this->template = $template;
        $this->cache = $cache;
    }

   public function anyIndex() {
		$this->template->clearAllCache();
        echo 'Smarty cache deleted.<br/>';
        $this->cache->clear();
        echo 'Stash cache deleted.<br/>';
    }    

}
