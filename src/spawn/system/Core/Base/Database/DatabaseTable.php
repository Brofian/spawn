<?php


namespace spawn\system\Core\Base\Database;


use spawn\system\Core\Base\Database\Storage\DatabaseAttributes;
use spawn\system\Core\Base\Database\Storage\DatabaseDefaults;
use spawn\system\Core\Base\Database\Storage\DatabaseIndex;
use spawn\system\Core\Base\Database\Storage\DatabaseType;
use spawn\system\Core\Base\Helper\DatabaseHelper;
use spawn\system\Core\Helper\FrameworkHelper\DatabaseStructureHelper;

abstract class DatabaseTable
{

    /** @var array  */
    protected $columns = array();

    /**
     * @var string
     */
    protected $foreignKey = '';

    /**
     * DatabaseTable constructor.
     * @param bool $hasId
     * @param bool $hasCreatedAt
     * @param bool $hasUpdatedAt
     */
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


    /**
     * @param DatabaseHelper $dbHelper
     * @return int
     */
    public function create(DatabaseHelper $dbHelper) {
        //init table
        $this->init();

        //create/update structure file
        $this->createStructureFile();

        //dont execute the rest of the table setup, if the table already exist
        if($dbHelper->doesTableExist($this->getTableName())) {
            return 2;
        }

        //run main script
        $sql = $this->getTableCreationSQL(DB_DATABASE);
        $dbHelper->query($sql);

        //run after creation script
        $this->afterCreation($dbHelper);
        return 0;
    }


    protected function createStructureFile() {
        DatabaseStructureHelper::createDatabaseStructure($this);
    }

    /**
     * @return bool
     */
    abstract public function init() : bool;

    /**
     * @return string
     */
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
        $col = new DatabaseColumn('id', DatabaseType::VARBINARY);
        $col->setAutoIncrement(true)
            ->setCanBeNull(true)
            ->setIndex(DatabaseIndex::PRIMARY);
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

    /**
     * @param string $databaseName
     * @return string
     */
    public function getTableCreationSQL(string $databaseName) : string {

        //Table Declaration
        $sql = "CREATE TABLE IF NOT EXISTS `".$databaseName."`.`".$this->getTableName()."` (";


        //Column Declarations
        $columnSqls = array();
        /** @var DatabaseColumn $column */
        foreach($this->getColumns() as $column) {
            $columnSqls[] = $column->getColumnCreationSQL();
        }
        $sql .= implode(',', $columnSqls);




        //set keys (indices)
        $keys = $this->getTableKeys();

        $isFirstKey = true;
        foreach($keys as $key => $values) {

            $sql .= ', ';
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


        if($this->foreignKey != '') {

            $sql .= ',';
            $sql .= $this->foreignKey;

        }


        $sql .= ")";

        return $sql;
    }


    /**
     * Returns the registered keys in the columns as a two-dimensional array
     * @return array
     */
    public function getTableKeys() : array {
        $keys = [];


        /** @var DatabaseColumn $column */
        foreach($this->getColumns() as $column) {

            //get the registered key for the current column
            $columnkey = $column->getIndex();
            if($columnkey == '') {
                continue;
            }

            //if there is no entry for this key now, create it
            if(!isset($keys[$columnkey])) {
                $keys[$columnkey] = [];
            }

            //add the column to the key list
            $keys[$columnkey][] = $column->getName();
        }

        return $keys;
    }

    /**
     * @param string $thisColumn
     * @param string $foreignTable
     * @param string $foreignColumn
     */
    public function setOnDeleteCascade(string $thisColumn, string $foreignTable, string $foreignColumn) {
        $this->foreignKey = 'FOREIGN KEY ('.$thisColumn.') REFERENCES '.$foreignTable.'('.$foreignColumn.') ON DELETE CASCADE';
    }

    /**
     * @param DatabaseHelper $dbhelper
     * @return mixed
     */
    public abstract function afterCreation(DatabaseHelper $dbhelper);

}