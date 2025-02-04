<?php

namespace Test;

abstract class AbstractIndexContainer extends Container {

    function getProps()
    {
        return [
            'test' => 'bool'
        ];
    }

}