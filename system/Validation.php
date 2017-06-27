<?php
/**
 * Input Validation class
 *
 * @package phreak
 * @author Simeon Lyubenov (ShakE) <lyubenov@gmail.com>
 * @link https://www.webdevlabs.com
 * @copyright Copyright (c) 2016 Simeon Lyubenov. All rights reserved.
 */

namespace System;

class Validation 
{
	use ValidationMethods;
	protected $fields = array();
	protected $customrules = array();
	private $errors = array();
	private $request;
	protected $formrequest;

	public function __construct($formrequest) 
	{
		$this->request = $formrequest;
	}

	/**
	 * Add Rule for validation
	 *
	 * @param string $field form field name
	 * @param string $errorMessage error message
	 * @param array $rules validation methods
	 * @return null
	 */
	public function addRule($field, $errorMessage, $rules = array()) 
	{
		if (count($this->request) == 0) {
			throw new \Exception("The array of post parameters is empty");
		}
		if ($field == '') {
			throw new \Exception("field parameter is empty");
		}
		if (!is_array($rules) || empty($rules)) {
			throw new \Exception("The array of rules parameter is empty");
		}
		$errorMessage = ($errorMessage == '') ? $field : $errorMessage;
		$this->fields[] = array(
			'name' => $field,
			'errorMessage' => $errorMessage,
			'rules' => $rules,
			);
	}

	/**
	 * Create custom Rule for validation
	 *
	 * @param string $rulename
	 * @param string $errorMessage
	 * @param string $callback function name
	 * @return null
	 */
	public function createRule($rulename, $errorMessage, $callback) 
	{
		$errorMessage = ($errorMessage == '') ? $rulename : $errorMessage;
		$this->customrules[] = array(
			'name' => $rulename,
			'errorMessage' => $errorMessage,
			'callback' => $callback);
	}

	/**
	 * run Validation
	 *
	 * @return boolean true/false for validation
	 */
	public function validate() 
	{
		if (count($this->request) == 0) {
			throw new \Exception("The array of post parameters is empty");
		}
		if (count($this->fields) == 0) {
			throw new \Exception("Validation rules is not set");
		}
		foreach ($this->fields as $field) {
			$fieldName = $field['name'];
			if (!isset($this->errors[$fieldName])) {
				$required = in_array('required', $field['rules']);
				foreach ($field['rules'] as $rule) {
					$param = false;
					if ($arr = explode('=', $rule)) {
						if (isset($arr[0]) && isset($arr[1])) {
							$param = $arr[1];
							$rule = $arr[0];
						}
					}
					$output = $this->$rule($this->request[$fieldName], $param);
					if (($required && $output === false) || ($required && !isset($this->request[$fieldName])) || (!$required && $output === false && !empty($this->request[$fieldName]))) {
						$this->errors[$fieldName] = $field['errorMessage'];
					}
				}
			}
		}

		// check for custom rules
		foreach ($this->customrules as $rule) {
			$output = call_user_func($rule['callback']);
			if ($output !== true && $rule['name'] !== '') {
				// if empty errorMessage, then get errors array from callback output
				if ($rule['name'] == $rule['errorMessage'] && is_array($output)) {
					//								$errnum='0';
					foreach ($output as $rulekey => $errmsg) {
						//									$errnum++;
						//									$this->errors[$rule['name'].'_'.$errnum] = $errmsg;
						if (!is_numeric($rulekey)) {
							// if there is key set inside error use it
							if (!isset($this->errors[$rulekey])) {
								$this->errors[$rulekey] = $errmsg;
							}
						} else {
							// if not, use the caller rule key
							if (!isset($this->errors[$rule['name']])) {
								$this->errors[$rule['name']] = $errmsg;
							}
						}
					}
				} else {
					if (!isset($this->errors[$rule['name']])) {
						$this->errors[$rule['name']] = $rule['errorMessage'];
					}
				}
			}
		}
		return (count($this->errors) == 0) ? true : false;
	}

	/**
	 * Get all validation errors
	 *
	 * @return array
	 */
	public function getErrors() 
	{
		$errors = array();
		if (!empty($this->errors)) {
			foreach ($this->errors as $key => $val) {
				$errors[$key] = $val;
			}
		}
		return $this->errors;
	}

	/**
	 * Get field validation errors
	 *
	 * @return array
	 */
	public function getError($field) 
	{
		if (isset($this->errors[$field]) && $this->errors[$field] != '') {
			return $this->errors[$field];
		}
		return false;
	}
	
}
