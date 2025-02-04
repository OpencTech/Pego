<?php
namespace Pego\_markers;
use marksync\provider\provider;
use Pego\Pego;
use Pego\Builder;

/**
 * @property-read Pego $pego
 * @property-read Builder $builder

*/
trait main {
    use provider;

   function createPego(): Pego { return new Pego; }
   function createBuilder(): Builder { return new Builder; }

}