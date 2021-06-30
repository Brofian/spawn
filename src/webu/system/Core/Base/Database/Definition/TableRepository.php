<?php declare(strict_types=1);

namespace webu\system\Core\Base\Database\Definition;

use webu\system\Core\Base\Database\DatabaseTable;
use webu\system\Core\Base\Database\DBAL\Criteria;

abstract class TableRepository {

    abstract public function getEntityClass() : string;

    public function __construct(DatabaseTable $databaseTableDefinition)
    {
        //TODO: save and use the Database Table class (which is used to create a table) as  the definition for a repository?
        //TODO: In this case: save values in a good format

        //TODO: Maybe add a reference to another tableRepository, that can be defined in the DatabaseTableDefinition?

        //TODO: Maybe migrate/execute the creation of the Database Table in this object, so no argument is necessary!!!
    }

    public function search(Criteria $criteria = null) : EntityCollection {
        /** @var EntityCollection $entityCollection */
        $entityCollection = new EntityCollection($this->getEntityClass());

        return $entityCollection;
    }

}