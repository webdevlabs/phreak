<?php
/**
 * Template functions
 *
 * @package phreak
 * @author Simeon Lyubenov <lyubenov@gmail.com>
 * @link http://www.lamez.org
 * @link https://www.webdevlabs.com
 */

namespace System;

class Template extends \Smarty {
    private $conf;
    private $language;

    public function __construct (Config $conf, Language $language) {
        parent::__construct();
        $this->conf = $conf;
        $this->language = $language;
        $this->loadSystem();
    }

    public function loadSystem () {
        $this->setCompileCheck(true); // set true to require smarty check if the template file is modified
        $this->force_compile = true; // set true only for debugging purposes

        $this->assign('requestURI',$_SESSION['requestURI']);
        $this->assign('language',$this->language->current);
        if ($this->language->default !== $this->language->current) {
            $baseurl = BASE_URL.'/'.$this->language->current;
        }else {
            $baseurl = BASE_URL;
        }		
        $this->assign('baseurl',$baseurl);

        $this->setTemplateDir($this->conf->template['template_dir'])
        ->setCompileDir($this->conf->template['compile_dir'])
        ->setCacheDir($this->conf->template['cache_dir'])
        ->setConfigDir($this->conf->template['languages_dir'])
        ->addPluginsDir($this->conf->template['plugins_dir']);

        $this->applyCacheSettings();

        $this->loadFilter('output', 'trimwhitespace'); // enable smarty internal html minifier

        // Set template variables
        $this->assign('conf',$this->conf);
        // Go through config/template.php "assign" section and assign template values 
        if (count($this->conf->template['assign'])) {
            foreach ($this->conf->template['assign'] as $tkey => $tval) {
                $this->assign($tkey, $tval);
            }
        }
    }

    /**
     * Override Smarty's built-in 'display' function
     *
     * @param string $template filename
     * @param string $cache_id
     * @param string $compile_id
     * @param string $parent
     * @return mixed
     */
    function display($template = null, $cache_id = null, $compile_id = null, $parent = null) {
        if ($this->conf->template['nocache'][$template]) {
            parent::clearCache($template);
        }
        if (!$cache_id) {
            $cache_id=$_SERVER['REQUEST_URI'];
        }
        parent::display($template, $cache_id, $compile_id, $parent);
    }

    /**
     * Set template notification message (FlashBag)
     *
     * @param string $message
     * @return null
     */
    function set_msg($message) {
        $_SESSION['msg'] = $message;
    }

    /**
     * Show template notification message (FlashBag)
     *
     * @return null
     */
    function show_msg() {
        $message = $_SESSION['msg'];
        unset($_SESSION['msg']);
        $this->assign('msg', $message);
    }

    public function applyCacheSettings()
    {
            // Cache settings
            if ($this->conf->template['cache_lifetime'] > 0) {
                $this->setCacheLifetime($this->conf->template['cache_lifetime']);
            } else {
                $this->setCacheLifetime(3600); // 1 hour
            }

            if ($this->conf->template['caching']!==false) {
                $this->setCaching(true);
                $this->setCompileCheck(false);                
            }
            switch ($this->conf->template['caching']) {
                case "redis":
                    $this->setCachingType('redis'); // Redis
                    break;
                case "memcache":
                    $this->setCachingType('memcache'); // Memcache Cache - /etc/default/memcached to enable sys daemon.
                    break;
                case "apc":
                    $this->setCachingType('apc'); // APC Cache
                    break;
                default:
                    $this->setCaching(false);
                    $this->setCompileCheck(true);
            }
    }
    // ---------- EOF CLASS.TEMPLATE.PHP
}
