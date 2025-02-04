<?php

namespace Test\project\Indexes;

use Test\project\ElasticConfigController;

abstract class AbstractElasticDocuments extends ElasticConfigController 
{

    /** 
     * Подсветка
    */
    function highlight(array $highlightTags = [  0 => '<mark>',  1 => '</mark>',], string | false $title = false, string | false $url = false, string | false $key = false, string | false $text = false, string | false $path = false) {
        return $this->handle('__highlight', [
			'highlightTags' => $highlightTags, 
			'title' => $title, 
			'url' => $url, 
			'key' => $key, 
			'text' => $text, 
			'path' => $path
		]);
    }

    /** 
     * Добавить данные в index
    */
    function add(string | false $title = false, string | false $url = false, string | false $key = false, string | false $text = false, string | false $path = false) {
        return $this->handle('__add', [
			'title' => $title, 
			'url' => $url, 
			'key' => $key, 
			'text' => $text, 
			'path' => $path
		]);
    }

    /** 
     * Поиск по содержимому
    */
    function match(string | false $title = false, string | false $url = false, string | false $key = false, string | false $text = false, string | false $path = false) {
        return $this->handle('__match', [
			'title' => $title, 
			'url' => $url, 
			'key' => $key, 
			'text' => $text, 
			'path' => $path
		]);
    }

    
    function bool(bool $title = false, bool $url = false, bool $key = false, bool $text = false, bool $path = false) {
        return $this->handle('__bool', [
			'title' => $title, 
			'url' => $url, 
			'key' => $key, 
			'text' => $text, 
			'path' => $path
		]);
    }

}