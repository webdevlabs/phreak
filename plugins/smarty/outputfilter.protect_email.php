<?php
/**
 * Smarty plugin.
 */

    /**
     * Encode email addresses template function.
     *
     * @param string $tpl_output
     * @param obj    $template   smarty template obj
     *
     * @return string
     */
    function protect_email($tpl_output)
    {
        $tpl_output = preg_replace('!(\S+)@([a-zA-Z0-9\.\-]+\.([a-zA-Z]{2,3}|[0-9]{1,3}))!', '$1<span class="hidden">'.rand().'</span>@<span class="hidden">'.rand().'</span>$2', $tpl_output);

        return $tpl_output;
    }
