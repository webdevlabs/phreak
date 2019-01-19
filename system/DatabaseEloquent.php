<?php

namespace System;

use Illuminate\Database\Capsule\Manager as Capsule;

class DatabaseEloquent
{
    public function __construct(Config $conf)
    {
        $this->conf = $conf;
        $this->connect();
    }

    public function connect()
    {
        $capsule = new Capsule();
        $capsule->addConnection([
           'driver'   => 'mysql',
           'host'     => $this->conf->database['mysql']['host'],
           'database' => $this->conf->database['mysql']['dbname'],
           'username' => $this->conf->database['mysql']['username'],
           'password' => $this->conf->database['mysql']['password'],
        ]);

        //Make this Capsule instance available globally.
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM.
        $capsule->bootEloquent();
        $capsule->bootEloquent();
    }
}
