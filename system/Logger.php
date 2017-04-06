<?php
/**
 * System Logger
 *
 * @package phreak
 * @author Simeon Lyubenov <lyubenov@gmail.com>
 * @link http://www.lamez.org
 * @link https://www.webdevlabs.com
 */
namespace System;

class Logger {

    /**
     * Write Log file
     *
     * @param string $file filename
     * @param string $type log type
     * @param string $content log message
     * @return null
     * @usage ;
     * $this->log->write('failed_logins','error','user failed to login');
     * $this->log->write('system.errors','warning','user is trying to select non existing language');
     */
    function write($file, $type, $content) {
        $backtrace = debug_backtrace();
        $caller = array_shift($backtrace);
        $caller['file'] = after(ROOT_DIR, $caller['file']);
        $ipaddr = $_SERVER['REMOTE_ADDR'];
        $ref = $_SERVER['HTTP_REFERER'];
        $requri = $_SERVER['REQUEST_URI'];
        $reqdata = $requri.($ref ? " Ref: $ref" : '');
        $today = date("dMY");
        $filename = ROOT_DIR."/storage/logs/$today-$file.log";
        $now = date('H:m:s');
        $content = "($type) <$now> (IP: $ipaddr) | Req: $reqdata | Src: $caller[file] (Line: $caller[line]) \n $content \n--------------------\n";
        if (!$handle = fopen($filename, 'a')) {
            $errMsg = "Cannot open file ($filename)";
        }
        // Write $somecontent to our opened file.
        if (fwrite($handle, $content) === false) {
            $errMsg = "Cannot write to file ($filename)";
            $this->write('log', 'error', $errMsg);
        }
        fclose($handle);
        return $errMsg;
    }

}
