<?php
/**
 * System Events
 * Based on https://github.com/sphido/events
 *
 * @package phreak
 * @author Simeon Lyubenov (ShakE) <lyubenov@gmail.com>
 * @link https://www.webdevlabs.com
 * @copyright Copyright (c) 2016 Simeon Lyubenov. All rights reserved.
 * 
 * @usage (with prioritization):
 * $this->event->on('eventName', function () { echo " stay hungry"; }, 200);
 * $this->event->on('eventName', function () { echo "stay foolish"; }, 100);
 * $this->event->trigger('eventName'); // print "stay foolish stay hungry"
 */

namespace System;

class Event {

	/**
	 * Return events object
	 *
	 * @return stdClass
	 */
	function events() {
		static $events;
		return $events ? : $events = new \stdClass();
	}

	/**
	 * Return listeners
	 *
	 * @param $event
	 * @return mixed
	 */
	function listeners($event) {
		if (isset($this->events()->$event)) {
			ksort($this->events()->$event);
			return call_user_func_array('array_merge', $this->events()->$event);
		}
	}

	/**
	 * Add event listener
	 *
	 * @param $event event name
	 * @param callable $listener
	 * @param int $priority
	 */
	function bind($event, callable $listener = null, $priority = 10) {
		$this->events()->{$event}[$priority][] = $listener;
	}

	/**
	 * Trigger only once.
	 *
	 * @param $event
	 * @param callable $listener
	 * @param int $priority
	 */
	function bind_once($event, callable $listener, $priority = 10) {
		$once = function ()use(&$once, $event, $listener) {
			$this->unbind($event, $once);
			return call_user_func_array($listener, func_get_args());
		}
		;

		$this->bind($event, $once, $priority);
	}

	/**
	 * Remove one or all listeners from event.
	 *
	 * @param $event
	 * @param callable $listener
	 * @return bool
	 */
	function unbind($event, callable $listener = null) {
		if (!isset($this->events()->$event))
			return;

		if ($listener === null) {
			unset($this->events()->$event);
		} else {
			foreach ($this->events()->$event as $priority => $listeners) {
				if (false !== ($index = array_search($listener, $listeners, true))) {
					unset($this->events()->{$event}[$priority][$index]);
				}
			}
		}

		return true;
	}

	/**
	 * Trigger events
	 *
	 * @param string|array $events
	 * @param array $args
	 * @return array
	 */
	function trigger($events, $args = array()) {
		$out = [];
		foreach ((array )$events as $event) {
			foreach ((array )$this->listeners($event) as $listener) {
				if (($out[] = call_user_func_array($listener, $args)) === false)
					break; // return false ==> stop propagation
			}
		}

		return $out;
	}

	/**
	 * Pass variable with all filters.
	 *
	 * @param string|array $events
	 * @param null $value
	 * @param array $args
	 * @return mixed|null
	 * @internal param null $value
	 */
	function filter($events, $value = null, $args = array()) {
		array_unshift($args, $value);
		foreach ((array )$events as $event) {
			foreach ((array )$this->listeners($event) as $listener) {
				$args[0] = $value = call_user_func_array($listener, $args);
			}
		}
		return $value;
	}

	/**
	 * @param $event
	 * @param callable $listener
	 * @param int $priority
	 */
	function add_filter($event, callable $listener, $priority = 10) {
		$this->bind($event, $listener, $priority);
	}

	/**
	 * Ensure that something will be handled
	 *
	 * @param string $event
	 * @param callable $listener
	 * @return mixed
	 */
	function ensure($event, callable $listener = null) {
		if ($listener)
			$this->bind($event, $listener, 0); // register default listener

		if ($listeners = $this->listeners($event)) {
			return call_user_func_array(end($listeners), array_slice(func_get_args(), 2));
		}
	}

}
