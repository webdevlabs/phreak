<?php
/**
 * Cache functions (currently not used).
 *
 * @author Simeon Lyubenov <lyubenov@gmail.com>
 *
 * @link http://www.lamez.org
 * @link https://www.webdevlabs.com
 */

namespace System;

class Cache extends \Stash\Pool
{
    private $conf;
    protected $driver;

    public function __construct(Config $conf)
    {
        $this->conf = $conf;
        $this->loadDriver();
    }

    public function loadDriver($type = null)
    {
        switch ($type) {
            case 'memcache':
                $cachedriver = new \Stash\Driver\Memcache([
                    'servers'=> [
                        $this->conf->cache['memcached']['host'],
                        $this->conf->cache['memcached']['port'],
                    ],
                ]);
            break;

            default:
                $cachedriver = new \Stash\Driver\FileSystem(['path'=>$this->conf->cache['stash']['cachedir']]);
        }
        $this->setDriver($cachedriver);
    }
}
