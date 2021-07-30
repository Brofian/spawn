<?php declare(strict_types=1);

namespace spawn\system\Core\Base\Database\Definition;


use spawn\system\Core\Base\Database\DatabaseConnection;
use spawn\system\Core\Base\Database\Query\QueryBuilder;

abstract class Entity {

    protected ?string $id = null;

    public abstract function getRepositoryClass() : string;


    /**
     * This function saves or updates this entity in the database
     */
    public function save(DatabaseConnection $connection) : bool {
        if($this->id !== null) {
            return $this->update();
        }

        /** @var TableRepository $tableRepository */
        $tableRepository = $this->getRepositoryClass();

        $qb = new QueryBuilder($connection);
        
        // TODO: somehow get the TableRepository (which contains the table definition) and pass the values
        // TODO: let all sql be executed inside if the repository

        return true;
    }

    /**
     * This function is called, when the entity already exists (aka has an id) and the save() function is called
     */
    protected function update() : bool {

        return true;
    }


}