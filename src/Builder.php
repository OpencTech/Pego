<?php

namespace Pego;

use Pego\_markers\Build;
use Pego\file\ClassInstance;
use Pego\file\Files;
use Pego\Schemas\SchemaItem;

class Builder
{
    use Build;

    static function make()
    {
        (new Builder())->build();
    }


    function build()
    {
        $files = new Files;

        $containers = $files->getProjectClassList(extends: PegoClass::class, isAbstract: true);
        if (empty($containers))
            exit("Config ненайден не найдены \n\n\n");

        /** @var ClassInstance $config */
        foreach ($containers as $config) {
            /** @var Build $target */
            $target = eval("return new class extends {$config->class} { use Pego\_markers\Build; };");

            $schema = $target->createScheme();

            /** @var SchemaItem $shemaItem */
            foreach ($schema->items as $shemaItem) {
                $target->classes->createAbstractInstance($config, $schema, $shemaItem);

                // if ($shemaItem->path)
            }

        }
    }









}
