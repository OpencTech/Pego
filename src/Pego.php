<?php

namespace Pego;

/**
 * Pego метод обязательно имень шаблон - public function __{$methodName}(...$props) {}
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class Pego {

    public function __construct(
        public string $method,
        public mixed $props = null
    ) {
        
    }
}