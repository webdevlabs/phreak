<?php
/**
 * Smarty plugin.
 */

    /**
     * CSS Combine + Minify template function.
     *
     * @param string $tpl_output
     * @param obj    $template   smarty template obj
     *
     * @return string
     */
    function combine_css($tpl_output)
    {
        $pma = preg_match_all('/<link[^>]*href="([^"]*)\.css"[^>]*>/', $tpl_output, $matches);
        if (($pma !== false && $pma > 0) && (!file_exists(ROOT_DIR.'/front/views/'.TEMPLATE.'/cache/front.css'))) {
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
