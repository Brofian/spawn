<?php

namespace modules\Berichtsheft\Database;

use webu\system\Core\Base\Database\DatabaseColumn;
use webu\system\Core\Base\Database\DatabaseTable;
use webu\system\Core\Base\Database\Storage\DatabaseIndex;
use webu\system\Core\Base\Database\Storage\DatabaseType;

class EntryTable extends DatabaseTable  {

    public function __construct()
    {
        $this->setColumns();
        parent::__construct();
    }


    //has to be set, or the table will be ignored
    public function getTableName() : string {
        return "entries";
    }


    //should be set to declare the columns
    public function setColumns() {
        $this->createIdColumn();


        // set a test column
        $col = new DatabaseColumn('test', DatabaseType::VARCHAR);
        $col->setLength(255)
            ->setCanBeNull(true)
            ->setIndex(DatabaseIndex::UNIQUE);
        $this->addColumn($col);


    }


}