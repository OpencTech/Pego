<?php

namespace Pego\Build;

use marksync\provider\Mark;
use Pego\_markers\Build;
use Pego\File\ClassInstance;
use Pego\Schemas\Schema;
use Pego\Schemas\SchemaItem;

#[Mark(args: ['parent'], mode: Mark::LOCAL)]
class Classes 
{
    use Build;

    function __construct(private $target) {
        
    }

    function createAbstractInstance(ClassInstance $config, Schema $schema, SchemaItem $shemaItem) // [ ] Продолжить
    {
        $classMethodsString = $this->classMethods->get($this->target, $shemaItem, $config->class);

        $useConfig = $schema->namespace != $config->namespace ? "use {$config->class};" : ''; 

        $className = $config->class;
        $classCode = <<<PHP
            <?php

            namespace {$schema->namespace};

            {$useConfig}

            abstract class {$className} extends {$config->className} 
            {
            
            {$classMethodsString}

            }
            PHP;

        $abstractFolder = implode(DIRECTORY_SEPARATOR, [$config->folderPath]);

        $abstractClassFileName = implode(DIRECTORY_SEPARATOR, [$config->folderPath, "{$className}.php"]);
        file_put_contents($abstractClassFileName, $classCode);

        echo "build class - $abstractClassFileName\n";
    }



    function getUserInstance()
    {

    }

}