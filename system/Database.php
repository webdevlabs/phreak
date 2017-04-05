<?php
namespace System;
use DI\Container;

class Database {
    public function __construct (Container $container) {
        $this->conf = $container->get('System\Config');
        $this->log = $container->get('System\Logger');
    }

    public function connect () {
        if ($this->conf->database) {
        /** Create a new PDO connection to MySQL **/
        try {
            $pdo = new \PDO(
                'mysql:dbname='.$this->conf->database['mysql']['dbname'].';
                    host='.$this->conf->database['mysql']['host'],
                            $this->conf->database['mysql']['username'], 
                            $this->conf->database['mysql']['password'],
                            [
                                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".$this->conf->database['mysql']['charset'],
                                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                            ]
            );
            $pdo->exec("SET CHARACTER SET ".$this->conf->database['mysql']['charset']);
            $pdo->exec("SET CHARACTER_SET_CONNECTION=".$this->conf->database['mysql']['charset']);
            $pdo->exec("SET SQL_MODE = ''");
        } catch (\PDOException $err) {
            $this->log->write('system','error','Unable to connect to database: ' . $err->getMessage());
        }

        return $pdo;    
    }
    }
}