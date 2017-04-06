<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsFilter
 */

	/**
	 * JS Combine template function
	 *
	 * @param string $tpl_output
	 * @param obj $template smarty template obj
	 * @return string
	 */
function combine_js($tpl_output) {
		$pma = preg_match_all('/<script[^>]*src="([^"]*)\.js"[^>]*><\/script>/', $tpl_output, $matches);
		if (($pma !== false && $pma > 0) && (!file_exists(ROOT_DIR.'/front/views/'.TEMPLATE.'/cache/front.js'))) {
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
