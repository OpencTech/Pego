<?php

namespace Pego;

use Pego\file\ClassInstance;
use Pego\file\Files;
use Pego\MethodArgs\Args;
use Pego\Schema\Schema;
use Pego\Schema\SchemaItem;
use ReflectionClass;

class Builder {

    static function make() 
    {
        (new Builder())->build();
    }


    function build() {
        $files = new Files;

        $pegoClasses = $files->getProjectClassList(extends: PegoClass::class,  isAbstract: true, postfix: ['Config']);
        $containers =  $files->getProjectClassList(extends: $pegoClasses, isAbstract: true);

        /** @var ClassInstance $config */
        foreach ($containers as $config) {
            $abstractContainersClass = $config->class;

            $methods = $this->getPegoMethods($abstractContainersClass);
            $target = eval("return new class extends $abstractContainersClass {};");

            /** @var Schema $schema */
            $schema = $target->createScheme(); 

            /** @var SchemaItem $item */
            foreach ($schema->items as $item) {
                $methodList = [];

                foreach ($methods as $method => ['method' => $run, 'props' => $props, 'doc' => $docs, 'args' => $args]) {
                    [$varibles, $array] = $this->getVaribles($target->{$run}($item, $props), $args);
                    $code = <<<PHP
                        {$docs}
                        function {$method}({$varibles}) {
                            return \$this->handle('__{$method}', {$array});
                        }
                    PHP;

                    $methodList[] = $code;
                }


                $classMethodsString = implode("\n\n", $methodList);
                $useConfigClass = $schema->namespace != $config->namespace ? 'use ' . $config->class . ';' : '';

                $classCode = <<<PHP
                <?php

                namespace {$schema->namespace};

                {$useConfigClass}

                abstract class {$item->abstractClass} extends {$config->className} 
                {
                
                {$classMethodsString}

                }
                PHP;

                $namespaceFolder = implode(DIRECTORY_SEPARATOR, [$config->folderPath, $schema->path]);
                if (!file_exists($namespaceFolder))
                    mkdir($namespaceFolder, 0777, true);

                $abstractClassFileName = implode(DIRECTORY_SEPARATOR, [$config->folderPath, $schema->path, "{$item->abstractClass}.php"]);
                file_put_contents($abstractClassFileName, $classCode);

                $classFileName = implode(DIRECTORY_SEPARATOR, [$config->folderPath, $schema->path, "{$item->class}.php"]);
                if (!file_exists($classFileName))
                    $this->createClass($classFileName, $item->class, $item->abstractClass, $schema->namespace);

                echo "build class - $classFileName\n";
            }

            
        }
    }


    private function createClass($fileName, $class, $extends, $namespace)
    {
        file_put_contents($fileName, <<<PHP
        <?php

        namespace {$namespace};
        
        class {$class} extends {$extends} {
            // code
        }
        PHP);
    }

    private function getVaribles(array $props, ?array $args = null)
    {
        $varibles = $args ? array_map(fn($arg) => "{$arg[0]} \${$arg[1]} = {$arg[2]}", $args) : [];
        $array = $args ? array_map(fn($arg) => "'{$arg[1]}' => \${$arg[1]}", $args) : [];

        foreach ($props as [$type, $var]) {
            $name = "\${$var}";
            $fullType = $type == 'bool' ? 'bool' : "$type | false";

            $varibles[] = "$fullType {$name} = false";
            $array[] = "'$var' => $name";
        }

        $variblesString = implode(', ', $varibles);
        $arrayString = "[\n\t\t\t" . implode(", \n\t\t\t", $array) . "\n\t\t]";

        return [$variblesString, $arrayString];
    }

    private function getPegoMethods(string $class) {
        $reflectionClass = new ReflectionClass($class);
        $result = [];

        foreach ($reflectionClass->getMethods() as $method) {
            if (!str_starts_with($method->name, '__')) 
                continue;
            

            $attributes = $method->getAttributes(Pego::class);
            if (!empty($attributes)) {
                $instance = $attributes[0]->newInstance();
                $name = substr($method->name, 2);
                $argss = new Args();

                $result[$name] = [
                    'method' => $instance->method, 
                    'props' => $instance->props, 
                    'doc' => $method->getDocComment(), 
                    'args' => $argss->get($method),
                ];
            }
            
        }

        return $result;
    }





}