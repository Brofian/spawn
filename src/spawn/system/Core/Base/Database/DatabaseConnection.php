<?php declare(strict_types=1);

namespace spawn\system\Core\Base\Database;

use PDO;

class DatabaseConnection
{

    protected static ?PDO $connection = null;

    /**
     * @param string $host
     * @param string $database
     * @param string $port
     * @param string $username
     * @param string $password
     * @return PDO
     */
    public static function createNewConnection(string $host, string $database, string $port, string $username, string $password): PDO
    {
        $pdo = new PDO("mysql:host=$host;dbname=$database;port=$port", $username, $password);
        if(MODE == 'dev') {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $pdo;
    }



    public static function getConnection(): PDO
    {
        if(self::$connection == null) {
            self::$connection = self::createNewConnection(
                DB_HOST, DB_DATABASE, DB_PORT, DB_USERNAME, DB_PASSWORD
            );
        }

        return self::$connection;
    }


}