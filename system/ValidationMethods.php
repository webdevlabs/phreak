<?php
/*** VALIDATION METHODS ***/

namespace System;

trait ValidationMethods {

	function validEmail($value) {
		return filter_var($value, FILTER_VALIDATE_EMAIL);
	}

	function required($value) {
		return ($value !== '') ? true : false;
	}

	function numeric($value) {
		return is_numeric($value) ? true : false;
	}

	function exactLength($value, $param) {
		return (strlen($value) == $param) ? true : false;
	}

	function minLength($value, $param) {
		return (strlen($value) >= $param) ? true : false;
	}

	function maxLength($value, $param) {
		return (strlen($value) <= $param) ? true : false;
	}

	function validURL($value) {
		return filter_var($value, FILTER_VALIDATE_URL);
	}

	function validIP($value) {
		return filter_var($value, FILTER_VALIDATE_IP);
	}

	function validInt($value) {
		return filter_var($value, FILTER_VALIDATE_INT);
	}

	function validBool($value) {
		return filter_var($value, FILTER_VALIDATE_BOOLEAN);
	}

	function match($value, $param) {
		return ($value == $param) ? true : false;
	}

	function validFloat($input) {
		return is_float($input) || ($input == (string )(float)$input);
	}

	function alpha($input) {
		return (preg_match("#^[a-zA-ZA-y]+$#", $input) == 1);
	}

	function alpha_numeric($input) {
		return (preg_match("#^[a-zA-ZA-y0-9]+$#", $input) == 1);
	}

	/* allow only alpha-numeric and _- */
	function validURI($input) {
		return (preg_match("#^[a-zA-ZA-y0-9_-]+$#", $input) == 1);
	}
}
