<?php

namespace Pego\MethodArgs;

use ReflectionMethod;
use ReflectionParameter;

class Args
{

    function get(ReflectionMethod $method)
    {
        $args = [];
        foreach ($method->getParameters() as $arg) {
            if ($arg->isVariadic())
                continue;

            $args[] = [$this->getType($arg), $arg->getName(), $this->getDefaultValue($arg)];
        }

        return $args;
    }


    private function getDefaultValue(ReflectionParameter $arg)
    {
        $defVal = $this->varexport($arg->getDefaultValue());
        $defVal = str_replace("\n", '', $defVal);

        return $defVal;
    }


    private function getType(ReflectionParameter $arg)
    {
        $atype = $arg->getType();
        $type = ($atype->allowsNull() ? '?' : '') . "$atype";

        return $type;
    }


    private function varexport($expression)
    {
        $export = var_export($expression, TRUE);
        $patterns = [
            "/array \(/" => '[',
            "/^([ ]*)\)(,?)$/m" => '$1]$2',
            "/=>[ ]?\n[ ]+\[/" => '=> [',
            "/([ ]*)(\'[^\']+\') => ([\[\'])/" => '$1$2 => $3',
        ];
        $export = preg_replace(array_keys($patterns), array_values($patterns), $export);
        return $export;
    }
}
