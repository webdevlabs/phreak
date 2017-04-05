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
		$bt = debug_backtrace();
		$caller = array_shift($bt);
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
			$err_msg = "Cannot open file ($filename)";
			echo $err_msg;
			exit;
		}
		// Write $somecontent to our opened file.
		if (fwrite($handle, $content) === false) {
			$err_msg = "Cannot write to file ($filename)";
			$this->write('log', 'error', $err_msg);
			echo $err_msg;
			exit;
		}
		fclose($handle);
	}

	/**
	 * Read Log file
	 *
	 * @param string $file filename
	 * @return array
	 */
	function read($file) {
		$filename = ROOT_DIR."/uploads/logs/$file";
		$contents = file($filename);
		$logs = array();
		foreach ($contents as $line_num => $line) {
			$log['ctime'] = before('|', $line);
			$log['line'] = after('|', $line);
			$log['line'] = nl2br("$log[line]");
			array_push($logs, $log);
		}
		$logs = sortArrayByField($logs, 'ctime', true);
		return $logs;
	}

}
?>