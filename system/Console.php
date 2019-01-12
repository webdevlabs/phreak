<?php
namespace System;

class Console
{
  public $namespace;

  public function __construct () 
  {
    if (PHP_SAPI !== 'cli' || @$_SERVER['argv'][0] !== 'console.php' || @$_SERVER['argv'][1] !== 'console') {
      header('HTTP/1.0 404 Not Found', true, 404);
      exit;
    }
  }

  public function run($command)
  {
    if (is_file(ROOT_DIR.strtolower(str_replace('\\', DIRECTORY_SEPARATOR, '/'.$this->namespace.'/').$command.'.php'))) {
      $clsname = $this->namespace.'\\'.$command;
      return new $clsname;
    } else {
      die('Command not found.');
    }
  }

}
