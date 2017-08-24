<?php
namespace Modules\Api\Controllers;

class Manage {

    public function __construct()
    {
        header('Content-Type: application/json');
        header('Accept: application/json');
    }

    public function postUpdate ()
    {
        echo 'success!';
    }

}
