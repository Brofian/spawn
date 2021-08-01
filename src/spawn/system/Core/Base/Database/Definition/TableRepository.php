<?php declare(strict_types=1);

namespace spawn\system\Core\Base\Database\Definition;

use Doctrine\DBAL\Types\Type;
use spawn\system\Core\Base\Database\DatabaseConnection;
use spawn\system\Core\Base\Database\DatabaseTable;
use spawn\system\Core\Base\Database\DBAL\Criteria;
use spawn\system\Core\Base\Database\Definition\TableDefinition\AbstractTable;
use spawn\system\Core\Helper\UUID;
use spawn\system\Throwables\WrongEntityForRepositoryException;

abstract class TableRepository {

    const TABLE_NAME = 'undefined';

    protected array $tableColumns = [];

    abstract public function getEntityClass() : string;

    public function __construct(
        AbstractTable $tableDefinition
    )
    {
        foreach($tableDefinition->getTableColumns() as $tableColumn) {
            $this->tableColumns[$tableColumn->getName()] = $tableColumn->getTypeIdentifier();
        }


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

    public function upsert(Entity $entity) {
        $this->verifyEntityClass($entity);

        if($entity->getId() === null) {
            $this->insert($entity);
        }
        else {
            $this->update($entity);
        }
    }

    protected function insert(Entity $entity): bool {
        $uuid = UUID::randomBytes();
        $now = new \DateTime();

        $entityArray = $entity->toArray();
        $entityArray['id'] = $uuid;
        $entityArray['createdAt'] = $now;
        $entityArray['updatedAt'] = $now;

        DatabaseConnection::getConnection()->insert(
            self::TABLE_NAME,
            $entityArray,
            $this->getTypeIdentifiersForColumns(array_keys($entityArray))
        );

        //set the id after the insert command in case of an error
        $entity->setId(UUID::bytesToHex($uuid));
        $entity->setCreatedAt($now);
        $entity->setUpdatedAt($now);
    }

    protected function update(Entity $entity): bool {
        $now = new \DateTime();

        $entityArray = $entity->toArray();
        $id = $entityArray['id'];
        unset($entityArray['id']);
        $entityArray['updatedAt'] = $now;

        DatabaseConnection::getConnection()->update(
            self::TABLE_NAME,
            $entityArray,
            [
                'id' => UUID::hexToBytes($id)
            ],
            $this->getTypeIdentifiersForColumns(array_keys($entityArray))
        );

        $entity->setUpdatedAt($now);
    }


    protected function getTypeIdentifiersForColumns(array $columns): array {
        $identifiers = [];
        foreach($columns as $column) {
            if(isset($this->tableColumns[$column])) {
               $identifiers[] = $this->tableColumns[$column];
            }
            else {
                $identifiers[] = \PDO::PARAM_NULL;
            }
        }
        return $identifiers;
    }

    protected function verifyEntityClass(Entity $entity) {
        $desiredEntityClass = $this->getEntityClass();
        if(!($entity instanceof $desiredEntityClass)) {
            throw new WrongEntityForRepositoryException(get_class($entity), $desiredEntityClass, self::class);
        }
    }


}