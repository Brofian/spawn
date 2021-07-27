<?php

namespace spawn\system\Core\Base\Database\Definition\TableDefinition;

class ColumnDefinition {

    protected string $nativeType;
    protected array $flags;
    protected string $table;
    protected string $name;
    protected int $length;
    protected int $precision;


    public function __construct(
        string $nativeType,
        array $flags,
        string $table,
        string $name,
        int $length,
        int $precision
    )
    {
        $this->nativeType = $nativeType;
        $this->flags = $flags;
        $this->table = $table;
        $this->name = $name;
        $this->length = $length;
        $this->precision = $precision;
    }



    public function equals(ColumnDefinition $other): bool {


    }



}