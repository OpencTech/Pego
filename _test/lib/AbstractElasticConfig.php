<?php

namespace Test\lib;

use Pego\Pego;
use Pego\PegoClass;

abstract class AbstractElasticConfig extends PegoClass {

    /** 
     * Подсветка
    */
    #[Pego]
    public function __highlight(array $highlightTags = ['<mark>', '</mark>'], null &...$props)
    {
        //** hl */

        return $this;
    }



    /** 
     * Добавить данные в index
    */
    #[Pego]
    public function __add(...$props)
    {
        
    }

    /** 
     * Поиск по содержимому
    */
    #[Pego('getProps', 'string')]
    public function __match(...$props)
    {
        
    }

    #[Pego('getProps', 'bool')]
    public function __bool(...$props)
    {
        
    }




    // function getSchema(): array 
    // {
    //     return [
    //         new SchemaItem('ElasticFilter', 'Filter', 'Поиск по фильтру', [
    //             'parent',
    //             'type',
    //             'section',
    //             'coll',
    //             'value',
    //             'valueId',
    //         ]),
    //         new SchemaItem('ElasticDocuments', 'Documents', 'Поиск по документам', [
    //             'title',
    //             'url',
    //             'key',
    //             'text',
    //             'path',
    //         ]),
    //     ];
    // }

    // function getProps(SchemaItem $item, $type = null) : array {
    //     $stringType = is_null($type) ? 'string | false' : "$type | false";
    //     return array_map(fn($coll) => "$stringType $coll = false", $item->props);
    // }

}