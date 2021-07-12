<?php declare(strict_types=1);

namespace spawn\system\Core\Base\Database\Definition;


abstract class Entity {

    protected ?string $id = null;

    public abstract function getRepositoryClass() : string;

    public abstract function loadFromResultArray($resultArray);


    /**
     * This function saves or updates this entity in the database
     */
    public function save() : bool {
        if($this->id === null) {
            return $this->update();
        }

        $sql = 'INSERT INTO ' . $this->getRepositoryClass();
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