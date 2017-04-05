<?php
namespace System;

class Database {
    public function __construct (Config $conf) {
        $this->conf = $conf;
    }

    public function connect () {
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
            die('Unable to connect to database: ' . $err->getMessage());
        }

        return $pdo;    
    }
}