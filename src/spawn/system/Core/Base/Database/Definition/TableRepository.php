<?php declare(strict_types=1);

namespace spawn\system\Core\Base\Database\Definition;


use Doctrine\DBAL\Exception;
use spawn\system\Core\Base\Database\DatabaseConnection;
use spawn\system\Core\Base\Database\Definition\TableDefinition\AbstractTable;
use spawn\system\Core\Helper\UUID;
use spawn\system\Throwables\WrongEntityForRepositoryException;

abstract class TableRepository {

    protected array $tableColumns = [];
    protected string $tableName;

    abstract public static function getEntityClass() : string;


    public function __construct(
        AbstractTable $tableDefinition
    )
    {
        foreach($tableDefinition->getTableColumns() as $tableColumn) {
            $this->tableColumns[$tableColumn->getName()] = $tableColumn->getTypeIdentifier();
        }

        $this->tableName = $tableDefinition->getTableName();
    }

    public function search(array $where = [], int $limit = 1000) : EntityCollection {
        $qb = DatabaseConnection::getConnection()->createQueryBuilder();
        $query = $qb->select('*')->from($this->tableName)->setMaxResults($limit);
        $whereFunction = 'where';
        foreach($where as $column => $value) {
            if(is_string($value)) {
                $query->$whereFunction("$column LIKE ':$column'")->setParameter(":$column", $value);
            }
            else {
                $query->$whereFunction("$column = :$column")->setParameter(":$column", $value);
            }

            $whereFunction = 'andWhere';
        }

        /** @var EntityCollection $entityCollection */
        $entityCollection = new EntityCollection($this->getEntityClass());

        try {
            $queryResult = $query->executeQuery();

            if(count($where)) {
                dump($query->fetchAssociative());
                dump($query->getSQL());
                dd($query->getParameters());
            }

            while($row = $queryResult->fetchAssociative()) {
                if(isset($row['id'])) {
                    $row['id'] = UUID::bytesToHex($row['id']);
                }
                $entityCollection->add($this->arrayToEntity($row));
            }
        } catch (Exception $e) {
            return $entityCollection;
        }


        return $entityCollection;
    }


    public function arrayToEntity(array $values): Entity {
        /** @var Entity $entityClass */
        $entityClass = $this->getEntityClass();
        return $entityClass::getEntityFromArray($values);
    }


    public function upsert(Entity $entity): bool {
        $this->verifyEntityClass($entity);

        if($entity->getId() === null) {
            return $this->insert($entity);
        }
        else {
            return $this->update($entity);
        }
    }

    protected function insert(Entity $entity): bool {
        $uuid = UUID::randomBytes();
        $now = new \DateTime();

        $entityArray = $entity->toArray();

        $entityArray['id'] = $uuid;
        if(isset($entityArray['createdAt'])) {
            $entityArray['createdAt'] = $now;
        }
        if(isset($entityArray['updatedAt'])) {
            $entityArray['updatedAt'] = $now;
        }


        try {
            DatabaseConnection::getConnection()->insert(
                $this->tableName,
                $entityArray,
                $this->getTypeIdentifiersForColumns(array_keys($entityArray))
            );
        }
        catch (\Exception $e) {
            return false;
        }

        //set the id after the insert command in case of an error
        $entity->setId(UUID::bytesToHex($uuid));
        $entity->setCreatedAt($now);
        $entity->setUpdatedAt($now);

        return true;
    }

    protected function update(Entity $entity): bool {
        $now = new \DateTime();

        $entityArray = $entity->toArray();
        $id = $entityArray['id'];
        unset($entityArray['id']);
        $entityArray['updatedAt'] = $now;

        try {
            DatabaseConnection::getConnection()->update(
                $this->tableName,
                $entityArray,
                [
                    'id' => UUID::hexToBytes($id)
                ],
                $this->getTypeIdentifiersForColumns(array_keys($entityArray))
            );
        }
        catch (\Exception $e) {
            return false;
        }


        $entity->setUpdatedAt($now);
        return true;
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