<?php
/**
 * Template functions
 *
 * @package bgCMS
 * @author Simeon Lyubenov (ShakE) <office@webdevlabs.com>
 * @link https://www.webdevlabs.com
 * @copyright Copyright (c) 2016 Simeon Lyubenov. All rights reserved.
 * @license NON-EXCLUSIVE LICENSE / Non-redistributable code
 * @note Web Development Labs reserves all intellectual property rights, including copyrights and trademark rights.
 */

namespace System;
use DI\Container;

class Template extends \Smarty {
	private $req;
	private $conf;

	public function __construct (Config $conf, Language $language) {
		parent::__construct();
		$this->conf = $conf;
		$this->setCompileCheck(true); // set true to require smarty check if the template file is modified
		$this->force_compile = false; // set true only for debugging purposes
		$requestURI = $_SESSION['requestURI'];
		$this->assign('requestURI',$requestURI);
		$this->assign('language',$language->current);
		$this->setTemplateDir(ROOT_DIR.'/App/views/')
		->setCompileDir(ROOT_DIR."/storage/cache/smarty")
		->setCacheDir(ROOT_DIR."/storage/cache/smarty")
		->setConfigDir(ROOT_DIR."/storage/languages")
		->addPluginsDir(ROOT_DIR."/plubins/smarty");

		// register basic internal functions
		$this->registerPlugin('function', "show_msg", array($this, 'show_msg'));
		$this->registerPlugin('function', "count", array($this, 'basic_count'));
		$this->registerPlugin('modifier', "roundmoney", array($this, 'roundmoney'));
//		$this->registerPlugin('modifier', "ago", 'ago');
		// if not logged in as admin
		if (!$_SESSION['admin_id'] > "0") {
			if ($this->conf->encode_output_emails == '1') {
				$this->registerFilter("output", array($this, 'protect_email')); // encode email addresses
			}
		}

		// if on frontend
//		if (!$this->url->inAdmin) {
		if ('tova_ne_e_vadmin'!=='da') {
			if ($this->conf->combine_js) {
				$this->registerFilter("output", array($this, 'combine_js')); // enable combine_js
			}
			if ($this->conf->combine_css) {
				$this->registerFilter("output", array($this, 'combine_css')); // enable combine_css
			}
			// Cache settings
			if ($this->conf->cache_lifetime > 0) {
				$this->setCacheLifetime($this->conf->cache_lifetime); // 1 hour
			} else {
				$this->setCacheLifetime(3600); // 1 hour
			}
			if ($this->conf->caching_file) {
				$this->setCaching(true);
				$this->setCompileCheck(false);
			}
			switch ($this->conf->caching_memory) {
				case "opcache":
					ini_set('opcache.use_cwd', true); // Enable to prevent collisions between files with the same base name.
					ini_set('opcache.validate_timestamps', true);
					ini_set('opcache.revalidate_freq', $this->conf->cache_lifetime);
					break;
				case "memcached":
					$this->setCachingType('memcache'); // Memcached Cache - /etc/default/memcached to enable sys daemon.
					break;
				case "apc":
					$this->setCachingType('apc'); // APC Cache
					break;
				default:
					ini_set('opcache.enable', 0);
			}

			if ($this->conf->minify_html_front == '1') {
				$this->loadFilter('output', 'trimwhitespace'); // enable smarty internal html minifier
			}
			//		$this->registerFilter("output",array($this,'async_css_load'));
		}

		// Set template variables
//		$this->assign('template', TEMPLATE); // assign front template name
		$this->assign('BASE_URL', BASE_URL);
//		define('BASE_PATH', after("http://".$_SERVER['HTTP_HOST'], BASE_URL));
		$this->assign('BASE_PATH', BASE_PATH);
		$this->assign('BASE_URL_SSL', 'https://'.after('http://', BASE_URL));
		$this->assign('conf',Config::$conf);
//		$this->assign('ref_url',$ref_url);
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
		if ($template == 'errors_js.tpl' or $template == 'errors.tpl') {
			parent::clearCache($template);
		}
		if (!$cache_id) {
//			$cache_id = md5($_SESSION['language'].'_'.$_SESSION['ref_url']);
		}
		parent::display($template, $cache_id, $compile_id, $parent);
	}

	/**
	 * Set template notification message
	 *
	 * @param string $message
	 * @return null
	 */
	function set_msg($message) {
		$_SESSION['msg'] = $message;
	}

	/**
	 * Show template notification message
	 *
	 * @return null
	 */
	function show_msg() {
		$message = $_SESSION['msg'];
		unset($_SESSION['msg']);
		$this->assign('msg', $message);
	}

	function basic_count($params) {
		return count($params['data']);
	}

	/**
	 * Round money string
	 *
	 * @param float $params
	 * @return float
	 */
	function roundmoney($params) {
		return number_format($params, 0, '.', ' ');
	}

	/**
	 * Fetch E-Mail template from DB
	 *
	 * @param string $template_name
	 * @param array $tvars template variables
	 * @return array
	 */
	public function email_template($template_name, $tvars) {
		// manualy load smarty resource email
		//		require_once(ROOT_DIR.'/includes/smarty_resource_email.php');

		// Parse Email Template
		$tpl_email = new_smarty('smartyonly');
		$tpl_email->force_compile = true;
		//  	$tpl_email->registerResource("email", new Smarty_Resource_Email());

		// assign additional template variables
		foreach ($tvars as $key => $val) {
			$tpl_email->assign("{$key}", $val);
		}
		//		$tpl_email->assign('password', $password);
		$subject = $tpl_email->fetch("email:subject/".$template_name);
		$email_message = $tpl_email->fetch("email:source/".$template_name);
		$from_email = DB::row("SELECT `from_email` from `email_templates` WHERE `tpl_name`='member_register'");
		return array(
			'from_email' => $from_email,
			'subject' => $subject,
			'message' => $email_message);
	}

	/**
	 * Encode email addresses template function
	 *
	 * @param string $tpl_output
	 * @param obj $template smarty template obj
	 * @return string
	 */
	function protect_email($tpl_output, \Smarty_Internal_Template $template) {
		$tpl_output = preg_replace('!(\S+)@([a-zA-Z0-9\.\-]+\.([a-zA-Z]{2,3}|[0-9]{1,3}))!', '$1<span class="hidden">'.rand().'</span>@<span class="hidden">'.rand().'</span>$2', $tpl_output);
		return $tpl_output;
	}

	/**
	 * Minify HTML template function
	 *
	 * @param string $tpl_output
	 * @param obj $template smarty template obj
	 * @return string
	 */
	function minify_html($tpl_output, \Smarty_Internal_Template $template) {
		$tpl_output = preg_replace('![\t ]*[\r\n]+[\t ]*!', '', $tpl_output);
		return $tpl_output;
	}

	/**
	 * CSS Combine + Minify template function
	 *
	 * @param string $tpl_output
	 * @param obj $template smarty template obj
	 * @return string
	 */
	function combine_css($tpl_output, \Smarty_Internal_Template $template) {
		$n = preg_match_all('/<link[^>]*href="([^"]*)\.css"[^>]*>/', $tpl_output, $matches);
		if (($n !== false && $n > 0) && (!file_exists(ROOT_DIR.'/front/views/'.TEMPLATE.'/cache/front.css'))) {
			$csscombined = "/* bgCMS Auto-Generated CSS File */\n";
			foreach ($matches[1] as $match) {
				if (strpos($match, BASE_URL) !== false) {
					// read all css files and combine them
					$csscombined .= file_get_contents(ROOT_DIR.after(BASE_URL, $match).'.css');
				}
			}
			file_put_contents(ROOT_DIR.'/front/views/'.TEMPLATE.'/cache/front.css', $csscombined);
		}
		$newsrc = $tpl_output;
		foreach ($matches[0] as $match) {
			if (strpos($match, BASE_URL) !== false) {
				$newsrc = str_replace($match, '', $newsrc);
			}
		}
		clearstatcache();
		$filectime = filectime(ROOT_DIR.'/front/views/'.TEMPLATE.'/cache/front.css');
		$newsrc = str_replace('</head>', '<link href="'.BASE_URL.'/front/views/'.TEMPLATE.'/cache/front.css?'.$filectime.'" rel="stylesheet">'.'</head>', $newsrc);
		return $newsrc;
	}
	/**
	 * JS Combine template function
	 *
	 * @param string $tpl_output
	 * @param obj $template smarty template obj
	 * @return string
	 */
	function combine_js($tpl_output, \Smarty_Internal_Template $template) {
		$n = preg_match_all('/<script[^>]*src="([^"]*)\.js"[^>]*><\/script>/', $tpl_output, $matches);
		if (($n !== false && $n > 0) && (!file_exists(ROOT_DIR.'/front/views/'.TEMPLATE.'/cache/front.js'))) {
			// create list with js files inside IF IE
			//   		preg_match_all('/if(.|\n)*?endif/', $tpl_output, $ifinsiders);
			$jscombined = "/* bgCMS Auto-Generated JS File */\n";
			foreach ($matches[1] as $match) {
				if (after_last('/', $match) == 'require') {
					continue;
				}
				if (strpos($match, BASE_URL) !== false) {
					// read all javascript files and combine them
					$jscombined .= file_get_contents(ROOT_DIR.after(BASE_URL, $match).'.js');
				}
			}
			file_put_contents(ROOT_DIR.'/front/views/'.TEMPLATE.'/cache/front.js', $jscombined);
		}
		$newsrc = $tpl_output;
		foreach ($matches[0] as $match) {
			if (strpos($match, 'require.js') !== false) {
				continue;
			}
			if (strpos($match, BASE_URL) !== false) {
				// read all javascript and remove from html source
				$newsrc = str_replace($match, '', $newsrc);
			}
		}
		clearstatcache();
		$filectime = filectime(ROOT_DIR.'/front/views/'.TEMPLATE.'/cache/front.js');
		$newsrc = str_replace('</body>', '<script src="'.BASE_URL.'/front/views/'.TEMPLATE.'/cache/front.js?'.$filectime.'" async></script>'.'</body>', $newsrc);
		return $newsrc;
	}
	function async_css_load($tpl_output, \Smarty_Internal_Template $template) {
		$n = preg_match_all('/<link[^>]*href="([^"]*)\.css"[^>]*>/', $tpl_output, $matches);
		$newsrc = $tpl_output;
		if ($n !== false && $n > 0) {
			foreach ($matches[1] as $match) {
				if (strpos($match, BASE_URL) !== false) {
					$newsrc = str_replace($match, '<link href="'.$match.'" rel="preload" as="style" onload="this.rel=\'stylesheet\'"><noscript><link rel="stylesheet" href="'.$match.'" rel="stylesheet"></noscript>', $newsrc);
				}
			}
		}
		return $newsrc;
	}
	// ---------- EOF CLASS.TEMPLATE.PHP
}
