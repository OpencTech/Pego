<?php
namespace Pego\_markers;
use marksync\provider\provider;
use Pego\Schemas\SchemaItem;
use Pego\Schemas\Schema;

/**
 * @property-read SchemaItem $schemaItem
 * @property-read Schema $schema

*/
trait Schemas {
    use provider;

   function createSchemaItem(): SchemaItem { return new SchemaItem; }
   function createSchema(): Schema { return new Schema; }

}