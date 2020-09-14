<?php

namespace webu\system\Core\Base\Helper;

use PDO;
use PDOException;
use webu\system\Core\Custom\Debugger;

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


    public function __construct() {

        $this->loadDBConfig();
        $this->createConnection();
    }


    private function loadDBConfig() {
        $this->host = DB_HOST;
        $this->username = DB_USERNAME;
        $this->password = DB_PASSWORD;
        $this->database = DB_DATABASE;
        $this->port = DB_PORT;
    }


    private function createConnection() {
        try {
            $this->connection = new PDO("mysql:host=".$this->host.";dbname=".$this->database.";port=".$this->port."",$this->username,$this->password);
        }
        catch(PDOException $pdoException) {
            $this->connection = false;
            Debugger::ddump('Cant connect to the database! Please check the credentials in the config.php file');
        }
    }


}