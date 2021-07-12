<?php

namespace webu\system\Core\Base\Database;

use PDO;

class DatabaseConnection
{

    /** @var PDO | false */
    private $connection = false;

    /**
     * DatabaseConnection constructor.
     * @param string $host
     * @param string $database
     * @param string $port
     * @param string $username
     * @param string $password
     */
    public function __construct(string $host, string $database, string $port, string $username = '', string $password = '')
    {
        $this->connection = new PDO("mysql:host=$host;dbname=$database;port=$port", $username, $password);
        if(MODE == 'dev') {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    /**
     * @return PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }


}