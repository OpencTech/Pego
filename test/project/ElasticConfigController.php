<?php

namespace Test\project;

use Pego\Schema\Schema;
use Pego\Schema\SchemaItem;
use Test\lib\AbstractElasticConfig;

abstract class ElasticConfigController extends AbstractElasticConfig {

    function createScheme(): Schema
    {
        return new Schema(
            './Indexes',
            'Test\project\Indexes',
            new SchemaItem('ElasticFilter', 'Filter', 'Поиск по фильтру', [
                'parent',
                'type',
                'section',
                'coll',
                'value',
                'valueId',
            ]),
            new SchemaItem('ElasticDocuments', 'Documents', 'Поиск по документам', [
                'title',
                'url',
                'key',
                'text',
                'path',
            ]),
        );
    }


    function getProps(SchemaItem $item, $type = null): array
    {
        $stringType = is_null($type) ? 'string' : $type;
        return array_map(fn($coll) => [$stringType, $coll], $item->props);
    }

}
