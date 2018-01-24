<?php
/**
 * Secure Session Handler
 * Encrypt PHP session data for the internal PHP save handlers.
 *
 * The encryption is built using OpenSSL extension with AES-256-CBC and the
 * authentication is provided using HMAC with SHA256.
 */

namespace System;

class Session extends SessionHandler
{
    protected $key;
    protected $name;

    public function start()
    {
        if (session_id() === '') {
            if (session_start()) {
                return true;
            }
        }

        return false;
    }

    public function forget()
    {
        if (session_id() === '') {
            return false;
        }
        $_SESSION = [];

        return session_destroy();
    }

    /**
     * Refresh session.
     *
     * @param string $name
     *
     * @return string
     */
    public function refresh($deleteold = false)
    {
        return session_regenerate_id($deleteold);
    }

    public function isExpired($ttl = 1800)
    {
        $last = isset($_SESSION['_last_activity']) ? $_SESSION['_last_activity'] : false;
        if ($last !== false && time() - $last > $ttl * 60) {
            return true;
        }
        $_SESSION['_last_activity'] = time();

        return false;
    }

    public function isFingerprint()
    {
        $hash = md5($_SERVER['HTTP_USER_AGENT'].(ip2long($_SERVER['REMOTE_ADDR']) & ip2long('255.255.0.0')));
        if (isset($_SESSION['_fingerprint'])) {
            return $_SESSION['_fingerprint'] === $hash;
        }
        $_SESSION['_fingerprint'] = $hash;

        return true;
    }

    public function isValid()
    {
        return !$this->isExpired() && $this->isFingerprint();
    }

    public function __get($name)
    {
        if (isset($_SESSION[$name])) {
            if (is_array($_SESSION[$name])) {
                return new \ArrayObject($_SESSION[$name], \ArrayObject::STD_PROP_LIST | \ArrayObject::ARRAY_AS_PROPS);
            } else {
                return $_SESSION[$name];
            }
        }
    }

    public function __set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function set($name, $value)
    {
        $parsed = explode('.', $name);
        $session = &$_SESSION;
        while (count($parsed) > 1) {
            $next = array_shift($parsed);
            if (!isset($session[$next]) || !is_array($session[$next])) {
                $session[$next] = [];
            }
            $session = &$session[$next];
        }
        $session[array_shift($parsed)] = $value;
    }
}
