<?php

namespace spawn\system\Core\Base\Database\Definition\TableDefinition;

use bin\spawn\IO;
use spawn\system\Core\Base\Database\DatabaseConnection;
use spawn\system\Throwables\ColumnMetaGatheringException;

abstract class AbstractTable {

    /**
     * @return AbstractColumn[]
     */
    abstract function getTableColumns(): array;

    abstract function getTableName(): string;

    protected final function getTableCreationSql() {
        $tableName = $this->getTableName();
        $columns = $this->getTableColumns();

        $columnDefinitions = [];
        $columnIndices = [];
        foreach($columns as $column) {
            $columnDefinitions[] = $column->getColumnDefinition();
            $columnIndices[] = $column->getIndexDefinition();
        }


        $sql  = "CREATE TABLE $tableName (";
        $sql .= implode(',', $columnDefinitions);
        if(!empty($columnIndices)) {
            $sql .= ',' . implode(',', $columnIndices);
        }
        $sql .= ")";

        return $sql;
    }

    protected final function getTableUpdateSql(array $columnsToUpdate): string {
        $add = $columnsToUpdate['add'];
        $drop = $columnsToUpdate['drop'];

        $tableName = $this->getTableName();
        $queries = [];

        if(!empty($add)) {
            $addQuery = "ALTER TABLE $tableName ADD ";
            $first = true;

            /** @var AbstractColumn $columnsToAdd */
            foreach($add as $columnsToAdd) {
                if($first) $first = false;
                else $addQuery .= ', ';

                $addQuery .= $columnsToAdd->getColumnDefinition();
            }
            $queries[] = $addQuery;
        }



        if(!empty($drop)) {
            $dropQuery = "ALTER TABLE $tableName ";
            $first = true;

            /** @var AbstractColumn $columnsToAdd */
            foreach($drop as $columnToDrop) {
                if($first) $first = false;
                else $dropQuery .= ' , ';

                $dropQuery .= "DROP COLUMN $columnToDrop";
            }
            $queries[] = $dropQuery;
        }



        return implode(';', $queries).';';
    }

    protected final function doesTableExist(\PDO $pdo): bool {
        $tableName = $this->getTableName();

        try {
            $result = $pdo->query("SELECT 1 FROM $tableName LIMIT 1");
        } catch (\PDOException $e) {
            // We got an exception == table not found
            return false;
        }

        // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
        return $result !== false;
    }

    /**
     * @return ColumnDefinition[]
     * @throws ColumnMetaGatheringException
     */
    protected final function getCurrentColumns(\PDO $pdo): array {
        $tableName = $this->getTableName();

        try {
            $stmt = $pdo->query("SELECT * FROM $tableName LIMIT 1");
            $columnCount = $stmt->columnCount();

            $columns = [];
            for($i = 0; $i < $columnCount; $i++) {
                $columnMeta = $stmt->getColumnMeta($columnCount);
                if($columnMeta) {
                    $columns[] = $columnMeta['name'];
                }
            }
        }
        catch(\Exception $e) {
            throw new ColumnMetaGatheringException($tableName);
        }

        return $columns;
    }

    protected final function getColumnChanges(\PDO $pdo) {
        $columnsToDrop = [];
        $columnsToAdd = [];

        $currentColumns = $this->getCurrentColumns($pdo);
        $desiredColumns = $this->getTableColumns();

        //find columns, that are not currently in the table, but should be
        foreach($desiredColumns as $desiredColumn) {
            if(!in_array($desiredColumn->getName(), $currentColumns)) {
                $columnsToAdd[] = $desiredColumn;
            }
        }

        //find columns, that currently are in the table, but should not be
        foreach($currentColumns as $currentColumn) {
            $shouldExist = false;
            foreach($desiredColumns as $desiredColumn) {
                if($desiredColumn->getName() == $currentColumn) {
                    $shouldExist = true;
                    break;
                }
            }

            if(!$shouldExist) {
                $columnsToDrop[] = $currentColumn;
            }
        }

        return [
            'drop' => $columnsToDrop,
            'add' => $columnsToAdd
        ];
    }


    public final function upsertTable() {

        $pdo = DatabaseConnection::getConnection();
        $sql = null;
        if(!$this->doesTableExist($pdo)) {
            IO::printLine(IO::TAB.':: Creating Table '. $this->getTableName() .' ::', IO::YELLOW_TEXT);
            $sql = $this->getTableCreationSql();
        }
        else {
            $requiredColumnChanges = $this->getColumnChanges($pdo);

            if(!empty($requiredColumnChanges['drop']) || !empty($requiredColumnChanges['add'])) {
                IO::printLine(IO::TAB.':: Updating Table '. $this->getTableName() .' ::', IO::YELLOW_TEXT);
                $sql = $this->getTableUpdateSql($requiredColumnChanges);
            }
        }

        if($sql !== null) {
            $pdo->query($sql);
            return true;
        }

        return false;
    }

}