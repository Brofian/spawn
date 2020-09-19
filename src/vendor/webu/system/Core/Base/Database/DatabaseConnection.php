<?php

namespace webu\system\Core\Base\Database;

use PDO;

class DatabaseConnection {

    /** @var PDO | false */
    private $connection = false;

    public function __construct(string $host, string $database, string $port, string $username = '', string $password = '')
    {
        $this->connection = new PDO("mysql:host=". $host.";dbname=".$database.";port=".$port."",$username,$password);
    }

    /**
     * @return PDO
     */
    public function getConnection() {
        return $this->connection;
    }




}