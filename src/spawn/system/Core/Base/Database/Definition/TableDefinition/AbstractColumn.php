<?php

namespace spawn\system\Core\Base\Database\Definition\TableDefinition;

use spawn\system\Core\Base\Database\Definition\TableDefinition\Constants\ColumnAttributes;
use spawn\system\Core\Helper\Slugifier;
use system\Core\Base\Database\Definition\TableDefinition\Constants\ColumnDefaults;

abstract class AbstractColumn {

    abstract public function getName(): string;

    abstract public function getType(): string;

    public function getLength(): ?int {
        return null;
    }

    public function getDefault(): string {
        return ColumnDefaults::NONE;
    }

    public function getAttribute(): string {
        return ColumnAttributes::NONE;
    }

    public function canBeNull(): bool {
        return true;
    }

    public function getIndex(): ?string {
        return null;
    }

    public function isAutoIncrement(): bool {
        return false;
    }

    public function getColumnDefinition(): string {
        $name = Slugifier::slugify($this->getName());
        $type = $this->getType();
        $length = ($this->getLength() !== null) ? '('.$this->getLength().')' : '';
        $attribute = $this->getAttribute();
        $null = $this->canBeNull() ? 'NULL' : 'NOT NULL';
        $default = $this->getDefault();
        $autoIncrement = $this->isAutoIncrement() ? 'AUTO_INCREMENT' : '';

        return "`$name` $type$length $attribute $null $default $autoIncrement";
    }

    public function getIndexDefinition(): string {
        $index = $this->getIndex();

        if($index === null) {
            return '';
        }

        $name = $this->getName();

        return "$index ($name)";
    }


}