<?php

namespace spawn\system\Core\Base\Database\Definition\TableDefinition;

use bin\spawn\IO;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use spawn\system\Core\Base\Database\DatabaseConnection;
use spawn\system\Core\Helper\Slugifier;


abstract class AbstractTable {

    public const PRIMARY_KEY_PREFIX = 'PK_';
    public const UNIQUE_INDEX_PREFIX = 'UI_';
    public const FOREIGN_KEY_PREFIX = 'FK_';

    /**
     * @return AbstractColumn[]
     */
    abstract function getTableColumns(): array;

    abstract function getTableName(): string;

    public final function upsertTable() {

        $connection = DatabaseConnection::getConnection();

        try {
            $schema = $connection->createSchemaManager()->createSchema();
            $oldSchema = clone $schema;

            if($schema->hasTable($this->getTableName())) {
                //update
                IO::printLine(IO::TAB.':: Updating Table '. $this->getTableName() .' ::', IO::YELLOW_TEXT);
                $this->updateTable($schema);
            }
            else {
                //create
                IO::printLine(IO::TAB.':: Creating Table '. $this->getTableName() .' ::', IO::YELLOW_TEXT);
                $this->createTable($schema);
            }


            $schemaDiffSql = $oldSchema->getMigrateToSql($schema, $connection->getDatabasePlatform());
            //$schemaDiffSql = array with all necessary sql queries

            foreach($schemaDiffSql as $sqlQuery) {
                $connection->executeQuery($sqlQuery);
            }

        }
        catch(Exception $e) {
            IO::printLine(IO::TAB.':: Error! Could mot create or update table "'. $this->getTableName() .'"! ::', IO::RED_TEXT);
            throw $e;
        }


        return false;
    }

    protected final function updateTable(Schema $schema) {
        try {
            $table = $schema->getTable($this->getTableName());

            $columnNames = [];
            foreach($this->getTableColumns() as $column) {
                $columnName = Slugifier::slugify($column->getName());
                $columnNames[] = $columnName;

                if($table->hasColumn($columnName)) {
                    //update column (^= remove and add the column again)
                    IO::printLine(IO::TAB.IO::TAB.':: Updating Column '. $columnName .' ::', IO::YELLOW_TEXT, 1);
                    $table->dropColumn($columnName);
                    $this->createColumnInTable($schema, $table, $column);
                }
                else {
                    //create column
                    IO::printLine(IO::TAB.IO::TAB.':: Creating Column '. $columnName .' ::', IO::YELLOW_TEXT, 1);
                    $this->createColumnInTable($schema, $table, $column);
                }
            }


            //remove old columns
            $currentColumns = $table->getColumns();
            foreach($currentColumns as $currentColumn) {
                $currentColumnName = $currentColumn->getName();

                if(!in_array($currentColumnName, $columnNames)) {
                    IO::printLine(IO::TAB.IO::TAB.':: Removing Column '. $currentColumnName .' ::', IO::YELLOW_TEXT, 1);

                    //drop foreign key
                    if($table->hasForeignKey(self::FOREIGN_KEY_PREFIX.$currentColumnName)) {
                        $table->removeForeignKey(self::FOREIGN_KEY_PREFIX.$currentColumnName);
                    }

                    //drop indices
                    if($table->hasIndex(self::UNIQUE_INDEX_PREFIX.$currentColumnName)) {
                        $table->dropIndex(self::UNIQUE_INDEX_PREFIX.$currentColumnName);
                    }

                    $table->dropColumn($currentColumnName);
                }
            }
        }
        catch(SchemaException $schemaException) {
            throw $schemaException;
        } catch (Exception $e) {
            throw $e;
        }

    }

    protected final function createTable(Schema $schema) {
        try {
            $schema->createTable($this->getTableName());
            $newTable = $schema->getTable($this->getTableName());

            foreach($this->getTableColumns() as $column) {
                IO::printLine(IO::TAB.IO::TAB.':: Creating Column '. Slugifier::slugify($column->getName()) .' ::', IO::YELLOW_TEXT, 1);
                $this->createColumnInTable($schema, $newTable, $column);
            }
        }
        catch (SchemaException $schemaException) {
            throw $schemaException;
        }
    }


    protected final function createColumnInTable(Schema $schema, Table $table, AbstractColumn $column) {
        try {
            $columnName = Slugifier::slugify($column->getName());

            $table->addColumn($columnName, $column->getType(), $column->getOptions());

            if($column->isPrimaryKey()) {
                IO::printLine(IO::TAB.IO::TAB.IO::TAB.':: Adding Primary Key for '. $columnName .' ::', IO::YELLOW_TEXT, 2);
                $table->setPrimaryKey([$columnName], self::PRIMARY_KEY_PREFIX.$columnName);
            }
            else if($column->isUnique()) {
                IO::printLine(IO::TAB.IO::TAB.IO::TAB.':: Adding Unique Index for '. $columnName .' ::', IO::YELLOW_TEXT, 2);
                $table->addUniqueIndex([$columnName], self::UNIQUE_INDEX_PREFIX.$columnName);
            }

            if($column->getForeignKeyConstraint()) {
                IO::printLine(IO::TAB.IO::TAB.IO::TAB.':: Adding Foreign Key Constraint for '. $columnName .' ::', IO::YELLOW_TEXT, 2);

                $foreignKeyConstraintData = $column->getForeignKeyConstraint();
                $remoteTableName = $foreignKeyConstraintData['table'];
                $remoteColumnName = $foreignKeyConstraintData['column'];
                $foreignKeyOptions = $foreignKeyConstraintData['options'];

                if($schema->hasTable($remoteTableName)) {
                    $remoteTable = $schema->getTable($remoteTableName);

                    if ($remoteTable->hasColumn($remoteColumnName)) {
                        $table->addForeignKeyConstraint(
                            $remoteTable,
                            [$column->getName()],
                            [$remoteColumnName],
                            $foreignKeyOptions
                        );
                    }
                }
            }
        }
        catch(SchemaException $schemaException) {
            throw $schemaException;
        }

    }


}