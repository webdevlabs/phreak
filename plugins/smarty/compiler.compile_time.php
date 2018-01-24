<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     compiler.compile_time.php
* Type:     compiler
* Name:     compile_time
* Purpose:  Output compile time
*
* Usage: {compile_time}
* Output: echo 'index.tpl compiled at 2002-02-20 20:02';
-------------------------------------------------------------
*/
function smarty_compiler_compile_time($tag_arg, &$smarty)
{
    $compile_time = round(microtime(true) - $_SESSION['master_load_start_time'], 3);
    $sqldebug = "\n- SQL Debug: Query Map -\n";
    foreach ($_SESSION['db_query_map'] as $qry) {
        $sqldebug .= $qry."\n";
    }

    return 'Compiled for '.$compile_time.'s at '.date('d-m-Y H:i').' (DB queries: '.$_SESSION['db_query_count'].')'.$sqldebug;
//    return "\n echo '" . $smarty->_current_file . " compiled at " . date('Y-m-d H:M'). "';";
}
