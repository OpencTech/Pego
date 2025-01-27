<?php

namespace Pego\file;

class ClassInstance {

    function __construct(
        public string $class, 
        public string $className, 
        public string $namespace,
        public string $folderPath,
        public string $filePath,
    )
    {
        
    }

    function __toString()
    {
        return $this->class;
    }

}