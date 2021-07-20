<?php declare(strict_types=1);

namespace spawn\system\Core\Base\Helper;

use PDOException;
use spawn\system\Core\Base\Database\DatabaseConnection;
use spawn\system\Core\Custom\Debugger;

class DatabaseHelper
{

    private string $host = '';
    private string $username = '';
    private string $password = '';
    private string $database = '';
    private string $port = '';
    private string $dbUrl = '';
    private ?DatabaseConnection $connection;

    /**
     * DatabaseHelper constructor.
     */
    public function __construct()
    {
        $this->loadDBConfig();
        $this->createConnection();
    }


    private function loadDBConfig()
    {
        $this->host = DB_HOST;
        $this->username = DB_USERNAME;
        $this->password = DB_PASSWORD;
        $this->database = DB_DATABASE;
        $this->port = DB_PORT;
    }


    private function createConnection()
    {
        try {
            $this->connection = new DatabaseConnection();
        } catch (PDOException $pdoException) {
            $this->connection = null;

            Debugger::ddump('Cant connect to the database! Please check the credentials in the config.php file');
        }
    }

    /**
     * @param $sql
     * @param bool $preventFetchAll
     * @return array|bool|false|\PDOStatement
     */
    public function query($sql, bool $preventFetchAll = false) {
        try {
            $result = $this->connection::getConnection()->query($sql);
        }
        catch(\Exception $e) {
            return false;
        }

        if(!$preventFetchAll && $result) {
            return $result->fetchAll();
        }

        return $result;
    }

    /**
     * @return DatabaseConnection
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * @param string $tablename
     * @return bool
     */
    public function doesTableExist(string $tablename) {
        $results = $this->query("SHOW TABLES LIKE '$tablename'");
        return sizeof($results) != 0;
    }

}