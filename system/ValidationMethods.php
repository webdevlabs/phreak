<?php

/*** VALIDATION METHODS ***/

namespace System;

trait ValidationMethods
{
    public function validEmail($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function required($value)
    {
        return ($value !== '') ? true : false;
    }

    public function numeric($value)
    {
        return is_numeric($value) ? true : false;
    }

    public function exactLength($value, $param)
    {
        return (strlen($value) == $param) ? true : false;
    }

    public function minLength($value, $param)
    {
        return (strlen($value) >= $param) ? true : false;
    }

    public function maxLength($value, $param)
    {
        return (strlen($value) <= $param) ? true : false;
    }

    public function validURL($value)
    {
        return filter_var($value, FILTER_VALIDATE_URL);
    }

    public function validIP($value)
    {
        return filter_var($value, FILTER_VALIDATE_IP);
    }

    public function validInt($value)
    {
        return filter_var($value, FILTER_VALIDATE_INT);
    }

    public function validBool($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function match($value, $param)
    {
        return ($value == $param) ? true : false;
    }

    public function validFloat($input)
    {
        return is_float($input) || ($input == (string) (float) $input);
    }

    public function alpha($input)
    {
        return preg_match('#^[a-zA-ZA-y]+$#', $input) == 1;
    }

    public function alpha_numeric($input)
    {
        return preg_match('#^[a-zA-ZA-y0-9]+$#', $input) == 1;
    }

    /* allow only alpha-numeric and _- */
    public function validURI($input)
    {
        return preg_match('#^[a-zA-ZA-y0-9_-]+$#', $input) == 1;
    }
}
