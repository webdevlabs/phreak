<?php

namespace App\Commands;
use System\DB;

class clearPushSubscriptions
{

    public function __construct () 
    {
        $results = DB::column("SELECT COUNT(*) from `push_subscriptions` WHERE `added` < (NOW() - INTERVAL 1 DAY)");
        echo 'Deleted '.$results.' records from database';
    }

}
