<?php
/**
 * Cache functions
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
				$cachedriver = new Stash\Driver\Memcache([
					'servers'=>[
						$config->cache['memcached']['host'],
						$config->cache['memcached']['port']
					]
				]);
			break;

			default:
				$cachedriver = new Stash\Driver\FileSystem(['path'=>$config->cache['stash']['cachedir']]);			
		}
		$cache = new Stash\Pool($cachedriver);
		return $cache;		
	}
}
