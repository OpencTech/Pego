<?php

namespace Pego;

use Pego\Schema\Schema;
use Pego\Schema\SchemaItem;

interface PegoInterface {

    function getProps(SchemaItem $item, $type = null) : array; // [$type, $var, $defaultValue]
    function createScheme(): Schema;

}