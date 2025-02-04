<?php

namespace Pego;

use Pego\Schemas\Schema;
use Pego\Schemas\SchemaItem;

interface PegoInterface {

    function getProps(SchemaItem $item, $type = null) : array; // [$type, $var, $defaultValue]
    function createScheme(): Schema;

}