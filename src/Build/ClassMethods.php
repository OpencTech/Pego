<?php

namespace Pego\Build;

use Pego\Pego;
use Pego\Schemas\SchemaItem;
use ReflectionClass;

class ClassMethods {

    function get($target, SchemaItem $shemaItem, string $abstractContainersClass)
    {
        $methods = $this->getPegoMethods($abstractContainersClass);
                

        $methodList = [];

        foreach ($methods as $method => ['method' => $run, 'props' => $props, 'args' => $args, 'docs' => $docs]) {
            $gen = $target->{$run}($shemaItem, $props);
            [$varibles, $array] = $this->getVaribles($args, $gen);
            $code = <<<PHP

                {$docs}
                function {$method}({$varibles}) 
                {
                    return \$this->handle('__{$method}', {$array});
                }
            PHP;

            $methodList[] = $code;
        }
        
        $result = implode("\n\n", $methodList);
        return $result;
    }


    private function getPegoMethods(string $class)
    {
        $reflectionClass = new ReflectionClass($class);
        $result = [];

        foreach ($reflectionClass->getMethods() as $method) {
            if (!str_starts_with($method->name, '__'))
                continue;
            

            $attributes = $method->getAttributes(Pego::class);
            if (!empty($attributes)) {
                $instance = $attributes[0]->newInstance();
                $name = substr($method->name, 2);
                $result[$name] = ['method' => $instance->method, 'props' => $instance->props, 'args' => $this->getMethodArgs($method), 'docs' => $method->getDocComment()];
            }
        }

        return $result;
    }




    private function getVaribles(array $props, array $gen)
    {
        $varibles = [];
        $array = [];

        foreach ([...$props, ...$gen] as [$type, $var, $default]) {
            $name = "\${$var}";

            $def = $this->exportVarible($default);

            $varibles[] = "$type {$name} = $def";
            $array[] = "'$var' => $name";
        }

        $variblesString = implode(', ', $varibles);
        $arrayString = '[' . implode(', ', $array) . ']';

        return [$variblesString, $arrayString];
    }



    private function getMethodArgs(\ReflectionMethod $method)
    {
        $args = [];

        foreach ($method->getParameters() as $param) {
            if ($param->isVariadic())
                continue;

            $type = (string)$param->getType();


            $args[] = [$type, $param->name, $this->exportVarible($param->getDefaultValue())];
        }

        return $args;
    }


    private function exportVarible($param)
    {
        $export = var_export($param, true);
        $patterns = [
            "/array \(/" => '[',
            "/^([ ]*)\)(,?)$/m" => '$1]$2',
            "/=>[ ]?\n[ ]+\[/" => '=> [',
            "/([ ]*)(\'[^\']+\') => ([\[\'])/" => '$1$2 => $3',
            "/\n/" => '',
        ];
        $export = preg_replace(array_keys($patterns), array_values($patterns), $export);

        return $export;
    }
}