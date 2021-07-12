<?php declare(strict_types=1);

namespace spawn\system\Core\Base\Database;

use PDO;

class DatabaseConnection
{

    private PDO $connection;

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