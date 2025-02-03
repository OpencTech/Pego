<?php

namespace Pego;

use Pego\file\Files;
use ReflectionClass;

class Builder
{

    static function make()
    {
        (new Builder())->build();
    }


    function build()
    {
        $files = new Files;

        $containers = $files->getProjectClassList(extends: PegoClass::class,  isAbstract: true);
        if (empty($containers))
            exit("Config ненайден не найдены \n\n\n");


        foreach ($containers as $info) {
            $abstractContainersClass = $info->class;

            $methods = $this->getPegoMethods($abstractContainersClass);
            $target = eval("return new class extends $abstractContainersClass {};");

            $methodList = [];

            foreach ($methods as $method => ['method' => $run, 'props' => $props]) {
                [$varibles, $array] = $this->getVaribles($target->{$run}(...($props ? $props : [])));
                $code = <<<PHP
                    function {$method}({$varibles}) {
                        return \$this->__{$method}(...{$array});
                    }
                PHP;

                $methodList[] = $code;
            }


            $className = $this->notAbstract($info->className);
            $classMethodsString = implode("\n\n", $methodList);
            $classCode = <<<PHP
            <?php

            namespace {$info->namespace};

            class {$className} extends {$info->className} 
            {
            
            {$classMethodsString}

            }
            PHP;

            $classFileName = implode(DIRECTORY_SEPARATOR, [$info->folderPath, "{$className}.php"]);
            file_put_contents($classFileName, $classCode);


            echo "build class - $classFileName\n";
        }
    }

    private function notAbstract(string $name)
    {
        $result = str_replace('Abstract', '', $name);
        if ($result == $name)
            throw new \Exception("Не удалось заменить \"Abstract\" - ($name)", 1);

        return $result;
    }

    private function getVaribles(array $props)
    {
        $varibles = [];
        $array = [];

        foreach ($props as $var => $type) {
            $name = "\${$var}";

            $varibles[] = "$type {$name}";
            $array[] = "'$var' => $name";
        }

        $variblesString = implode(', ', $varibles);
        $arrayString = '[' . implode(', ', $array) . ']';

        return [$variblesString, $arrayString];
    }

    private function getPegoMethods(string $class)
    {
        $reflectionClass = new ReflectionClass($class);
        $result = [];

        foreach ($reflectionClass->getMethods() as $method) {
            if (!str_starts_with($method->name, '__')) {

                continue;
            }

            $attributes = $method->getAttributes(Pego::class);
            if (!empty($attributes)) {
                $instance = $attributes[0]->newInstance();
                $name = substr($method->name, 2);
                $result[$name] = ['method' => $instance->method, 'props' => $instance->props];
            }
        }

        return $result;
    }
}
