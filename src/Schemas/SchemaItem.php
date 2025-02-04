<?php

namespace Pego\Schemas;

class SchemaItem {

    public string $abstractClass;
    function __construct(public string $class, public string $name, public string $description, public array $props)
    {
        $this->abstractClass = "Abstract{$class}";
    }

}