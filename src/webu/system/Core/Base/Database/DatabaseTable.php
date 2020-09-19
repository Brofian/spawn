<?php


namespace webu\system\Core\Base\Database;


use webu\system\Core\Base\Database\Storage\DatabaseAttributes;
use webu\system\Core\Base\Database\Storage\DatabaseDefaults;
use webu\system\Core\Base\Database\Storage\DatabaseIndex;
use webu\system\Core\Base\Database\Storage\DatabaseType;

abstract class DatabaseTable
{

    /** @var array  */
    protected $columns = array();


    public function __construct(bool $hasId = true, bool $hasCreatedAt = true, bool $hasUpdatedAt = true)
    {
        if($hasId) {
            $this->createIdColumn();
        }
        if($hasCreatedAt) {
            $this->createCreatedAtColumn();
        }
        if($hasUpdatedAt) {
            $this->createUpdatedAtColumn();
        }
    }


    abstract public function init() : bool;

    abstract public function getTableName() : string;



    /**
     * Add a new column to the table
     * @param DatabaseColumn $column
     * @return bool
     */
    protected function addColumn(DatabaseColumn $column) : bool {
        if(in_array($column->getName(), $this->getColumnNames())) {
            return false;
        }
        $this->columns[] = $column;
        return true;
    }

    /**
     * Adds an default Id-Column to the table
     * @return bool
     */
    protected function createIdColumn() {
        $col = new DatabaseColumn('id', DatabaseType::INT);
        $col->setAutoIncrement(true)
            ->setCanBeNull(true)
            ->setIndex(DatabaseIndex::INDEX);
        return $this->addColumn($col);
    }

    /**
     * Adds an default Created-At-Column to the table
     * @return bool
     */
    protected function createCreatedAtColumn() {
        $col = new DatabaseColumn('created_at', DatabaseType::DATETIME);
        $col->setCanBeNull(true)
            ->setDefault(DatabaseDefaults::CURRENT_TIMESTAMP);
        return $this->addColumn($col);
    }

    /**
     * Adds an default Updated-at-Column to the table
     * @return bool
     */
    protected function createUpdatedAtColumn() {
        $col = new DatabaseColumn('updated_at', DatabaseType::DATETIME);
        $col->setCanBeNull(true)
            ->setDefault(DatabaseDefaults::CURRENT_TIMESTAMP)
            ->setAttribute(DatabaseAttributes::ONUPDATE);
        return $this->addColumn($col);
    }

    /**
     * Get the column names as an string array
     * @return array
     */
    public function getColumnNames() : array {
        $columnNames = array();
        foreach($this->columns as $column) {
            $columnNames[] = $column->getName();
        }
        return $columnNames;
    }

    /**
     * Gets the number of registered columns
     * @return int
     */
    public function getColumnCount() : int {
        return sizeof($this->columns);
    }

    /**
     * returns the columns array
     * @return array
     */
    public function getColumns() : array {
        return $this->columns;
    }

    public function getTableCreationSQL(string $databaseName) : string {

        //Table Declaration
        $sql = "CREATE TABLE IF NOT EXISTS `".$databaseName."`.`".$this->getTableName()."` (";


        //Column Declarations

        $isFirst = true;
        /** @var DatabaseColumn $column */
        foreach($this->getColumns() as $column) {

            $sql .= $column->getColumnCreationSQL();
            $sql .= ", ";

        }


        //set keys (indices)
        $keys = $this->getTableKeys();

        $isFirstKey = true;
        /** @var DatabaseColumn $column */
        foreach($keys as $key => $values) {

            if($isFirstKey) {
                $isFirstKey = false;
            }
            else {
                $sql .= ',';
            }
            $sql .= $key . '(';

            $isFirstValue = true;
            foreach($values as $value) {
                if($isFirstValue) {
                    $isFirstValue = false;
                }
                else {
                    $sql .= ", ";
                }
                $sql .= "`" . $value . "`";

            }

            $sql .= ')';

        }

        $sql .= ")";

        return $sql;
    }


    /**
     * Returns the registered keys in the columns
     * @return array
     */
    public function getTableKeys() : array {
        $keys = [];


        /** @var DatabaseColumn $column */
        foreach($this->getColumns() as $column) {
            $index = $column->getIndex();
            if($index == '') {
                continue;
            }

            if(!isset($keys[$index])) {
                $keys[$index] = [];
            }

            $keys[$index][] = $column->getName();
        }

        return $keys;
    }

}