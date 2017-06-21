<?php

namespace Pho\Kernel\Standards;

use Pho\Framework;
use Pho\Kernel\Kernel;

class Graph extends Group {

    public function __construct(Kernel $kernel, Framework\Actor $creator)
    { 
        parent::__construct($kernel, $creator, $kernel["universe"]);
        $creator->changeContext($this);
    }

}