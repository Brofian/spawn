<?php declare(strict_types=1);

namespace spawn\system\Core\Base\Database\Definition;


abstract class Entity {

    protected ?string $id = null;

    public abstract function getRepositoryClass() : string;

    abstract public function toArray(): array;

    abstract public static function getEntityFromArray(array $values): Entity;

    public function getId(): ?string {
        return $this->id;
    }

    public function setId(?string $id): self {
        $this->id = $id;
        return $this;
    }

}