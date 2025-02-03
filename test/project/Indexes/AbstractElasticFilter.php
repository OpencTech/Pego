<?php

namespace Test\project\Indexes;

use Test\project\ElasticConfig;

abstract class AbstractElasticFilter extends ElasticConfig 
{

    /** 
     * Подсветка
    */
    function highlight(array $highlightTags = array (  0 => '<mark>',  1 => '</mark>',), string | false $parent = false, string | false $type = false, string | false $section = false, string | false $coll = false, string | false $value = false, string | false $valueId = false) {
        return $this->handle('__highlight', [
			'highlightTags' => $highlightTags, 
			'parent' => $parent, 
			'type' => $type, 
			'section' => $section, 
			'coll' => $coll, 
			'value' => $value, 
			'valueId' => $valueId
		]);
    }

    /** 
     * Добавить данные в index
    */
    function add(string | false $parent = false, string | false $type = false, string | false $section = false, string | false $coll = false, string | false $value = false, string | false $valueId = false) {
        return $this->handle('__add', [
			'parent' => $parent, 
			'type' => $type, 
			'section' => $section, 
			'coll' => $coll, 
			'value' => $value, 
			'valueId' => $valueId
		]);
    }

    /** 
     * Поиск по содержимому
    */
    function match(string | false $parent = false, string | false $type = false, string | false $section = false, string | false $coll = false, string | false $value = false, string | false $valueId = false) {
        return $this->handle('__match', [
			'parent' => $parent, 
			'type' => $type, 
			'section' => $section, 
			'coll' => $coll, 
			'value' => $value, 
			'valueId' => $valueId
		]);
    }

    
    function bool(bool $parent = false, bool $type = false, bool $section = false, bool $coll = false, bool $value = false, bool $valueId = false) {
        return $this->handle('__bool', [
			'parent' => $parent, 
			'type' => $type, 
			'section' => $section, 
			'coll' => $coll, 
			'value' => $value, 
			'valueId' => $valueId
		]);
    }

}