<?php
namespace Pego\_markers;
use marksync\provider\provider;
use Pego\File\Files;
use Pego\File\ClassInstance;

/**
 * @property-read Files $files
 * @property-read ClassInstance $classInstance

*/
trait File {
    use provider;

   function createFiles(): Files { return new Files; }
   function createClassInstance(): ClassInstance { return new ClassInstance; }

}