<?php

namespace webu\system\Core\Base\Helper;

use PDO;
use PDOException;

class DatabaseHelper {

    /** @var string  */
    private $host       = '';
    /** @var string  */
    private $username   = '';
    /** @var string  */
    private $password   = '';
    /** @var string  */
    private $database   = '';
    /** @var string  */
    private $port       = '';
    /** @var string  */
    private $dbUrl = '';
    /** @var PDO | false */
    private $connection = false;

    public function __construct($config) {

        $this->readConfig($config);
        $this->createConnection();

    }

    private function readConfig($config) {
        $this->host = $config["db"]["host"];
        $this->username = $config["db"]["username"];
        $this->password = $config["db"]["password"];
        $this->database = $config["db"]["database"];
        $this->port = $config["db"]["port"];
    }

    private function createConnection() {
        try {
            $this->connection = new PDO("mysql:host=".$this->host.";dbname=".$this->database.";port=".$this->port."",$this->username,$this->password);
        }
        catch(PDOException $pdoException) {
            $this->connection = false;
        }
    }



}