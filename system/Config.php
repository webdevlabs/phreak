<?php
/**
 * System Config builder.
 *
 * @author Simeon Lyubenov <lyubenov@gmail.com>
 *
 * @link http://www.lamez.org
 * @link https://www.webdevlabs.com
 */

namespace System;

class Config
{
    public $conf;
    private $configFiles;

    /**
     * Create system config object and set some defaults.
     */
    public function __construct()
    {
        $this->configFiles = [
            'system'  => ROOT_DIR.'/config/system.php',
            'cache'   => ROOT_DIR.'/config/cache.php',
            'database'=> ROOT_DIR.'/config/database.php',
            'session' => ROOT_DIR.'/config/session.php',
            'template'=> ROOT_DIR.'/config/template.php',
        ];
        $this->conf = $this->build($this->conf);
    }

    /**
     * Magic getter.
     *
     * @param [string] $name
     *
     * @return string|array
     */
    public function __get($name)
    {
        if (@array_key_exists($name, $this->conf)) {
            return $this->conf[$name];
        }
    }

    /**
     * Build config
     * load all configFiles.
     *
     * @param [array] $conf
     *
     * @return array
     */
    public function build($conf)
    {
        foreach ($this->configFiles as $cfgkey => $cfgfile) {
            if (is_readable($cfgfile)) {
                $conf[$cfgkey] = include $cfgfile;
            }
        }

        return $conf;
    }

    /**
     * Append config file to global system config.
     *
     * @param [string] $cfgkey
     * @param [string] $cfgfile
     *
     * @return null
     *
     * usage:
     * $config->append('admin','modules/admin/config.php');
     */
    public function append($cfgkey, $cfgfile)
    {
        if (is_readable($cfgfile)) {
            $this->conf[$cfgkey] = include $cfgfile;
        }
    }
}
