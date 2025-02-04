<?php
namespace Pego\_markers;
use marksync\provider\provider;
use Pego\Build\ClassMethods;
use Pego\Build\Classes;

/**
 * @property-read ClassMethods $classMethods
 * @property-read Classes $classes

*/
trait Build {
    use provider;

   function createClassMethods(): ClassMethods { return new ClassMethods; }
   function _createClasses(): Classes { return new Classes($this); }

}