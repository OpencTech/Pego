<?php

namespace Test;

use Pego\Pego;
use Pego\PegoClass;

abstract class Container extends PegoClass {

    #[Pego('getProps')]
    public function __getInfo(...$props)
    {
        
    }


}