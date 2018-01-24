<?php
/**
 * Phreak! - the ultimate development tool for lamez.
 *
 * ultralight fast PHP HMVC framework
 *
 * @author Simeon Lyubenov <lyubenov@gmail.com>
 *
 * @link http://www.lamez.org
 * @link https://www.webdevlabs.com
 */
require_once __DIR__.'/../bootstrap.php';

// Emit the response
$emitter = new \Zend\Diactoros\Response\SapiEmitter();
$emitter->emit($response);
