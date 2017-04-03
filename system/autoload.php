<?php
/* autoload with namespaces as dir */
function AutoLoader($className) {
    $path = str_replace('\\',DIRECTORY_SEPARATOR,$className);
    $file = after_last(DIRECTORY_SEPARATOR,$path);
    $dir = strtolower(before($file,$path));
    $file = $dir.$file;
//  	echo 'load '.ROOT_DIR.DIRECTORY_SEPARATOR.$file.'.php<br />';
    if (is_readable(ROOT_DIR.DIRECTORY_SEPARATOR.$file.'.php')) {
//	  	echo 'readit<br />';
			require_once ROOT_DIR.DIRECTORY_SEPARATOR.$file.'.php';
    }
}
spl_autoload_register('AutoLoader');