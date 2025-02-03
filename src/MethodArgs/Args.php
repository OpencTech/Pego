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
        $defVal = var_export($arg->getDefaultValue(), true);
        $defVal = str_replace("\n", '', $defVal);

        return $defVal;
    }


    private function getType(ReflectionParameter $arg)
    {
        $atype = $arg->getType();
        $type = ($atype->allowsNull() ? '?' : '') . "$atype";

        return $type;
    }
}
