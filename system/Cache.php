<?php
/**
 * Cache functions (currently not used)
 *
 * @package phreak
 * @author Simeon Lyubenov <lyubenov@gmail.com>
 * @link http://www.lamez.org
 * @link https://www.webdevlabs.com
 */

namespace System;

class Cache 
{
	private $conf;

	public function __construct (Config $conf) 
	{
		$this->conf = $conf;
	}

	public function init ($driver) 
	{
		switch ($driver) {			
			case 'memcache':
				$cachedriver = new \Stash\Driver\Memcache([
					'servers'=>[
						$this->conf->cache['memcached']['host'],
						$this->conf->cache['memcached']['port']
					]
				]);
			break;

			default:
				$cachedriver = new \Stash\Driver\FileSystem(['path'=>$this->conf->cache['stash']['cachedir']]);			
		}
		$cache = new \Stash\Pool($cachedriver);
		return $cache;		
	}
}
