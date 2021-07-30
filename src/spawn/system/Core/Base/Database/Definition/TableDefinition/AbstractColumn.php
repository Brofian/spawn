<?php

namespace spawn\system\Core\Base\Database\Definition\TableDefinition;

use spawn\system\Core\Base\Database\Definition\TableDefinition\Constants\ColumnTypeOptions;
use system\Core\Base\Database\Definition\TableDefinition\Constants\ColumnDefaults;

abstract class AbstractColumn {

    abstract public function getName(): string;

    abstract public function getType(): string;


    /**
     * @return string|int
     */
    public function getDefault() {
        return ColumnDefaults::NONE;
    }

    public function isUnique(): bool {
        return false;
    }

    public function isPrimaryKey(): bool {
        return false;
    }

    public function getForeignKeyConstraint(): ?ForeignKey {
        return null;
    }

    protected function canBeNull(): ?bool {
        return null;
    }

    protected function isUnsigned(): ?bool {
        return null;
    }

    protected function isAutoIncrement(): ?bool {
        return null;
    }

    protected function getLength(): ?int {
        return null;
    }

    protected function hasFixedLength(): ?bool {
        return null;
    }

    protected function getPrecision(): ?int {
        return null;
    }

    protected function getScale(): ?int {
        return null;
    }

    public function getOptions(): array {
        $givenOptions = [
            'notnull' => $this->canBeNull(),
            'length' => $this->getLength(),
            'unsigned' => $this->isUnsigned(),
            'default' => $this->getDefault(),
            'autoincrement' => $this->isAutoIncrement(),
            'fixed' => $this->hasFixedLength(),
            'precision' => $this->getPrecision(),
            'scale' => $this->getScale()
        ];
        $requiredOptions = ColumnTypeOptions::getOptionsForType($this->getType());

        $options = [];
        foreach($requiredOptions as $optionKey) {
            if(isset($givenOptions[$optionKey]) && $givenOptions[$optionKey] !== null) {
                $options[$optionKey] = $givenOptions[$optionKey];
            }
        }

        return $options;
    }

}