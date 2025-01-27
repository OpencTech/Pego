<?php

namespace Test;

class IndexContainer extends AbstractIndexContainer 
{

    function getInfo(bool $test) {
        return $this->__getInfo(...['test' => $test]);
    }

}