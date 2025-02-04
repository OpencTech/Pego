<?php

namespace Pego\Schemas;

class Schema {
    public array $items = [];

    function __construct(public string $path, public string $namespace, SchemaItem ...$items)
    {
        $this->items = $items;
    }

}