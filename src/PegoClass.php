<?php

namespace Pego;

abstract class PegoClass implements PegoInterface
{

    public string $name;

    function handle(string $method, array $props)
    {
        return $this->{$method}(...$this->removeDefaults($props));
    }


    private function removeDefaults(array $props): array
    {
        $result = [];



        return $result;
    }
}
