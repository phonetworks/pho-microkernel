<?php

namespace Pho\Kernel\Hooks;

use Pho\Lib\Graph;

class Edge
{
    public static function setup(EdgeInterface $edge): void
    {
        $edge->hook("head", function(): Graph\NodeInterface  {
            $this->head = $this->kernel->gs()->node($this->head_id);
            return $this->head;
        });
        $edge->hook("tail", function(): Graph\NodeInterface {
            $this->tail =  $this->kernel->gs()->node($this->tail_id);
            return $this->tail;
        });
        $edge->hook("predicate", function(): Graph\PredicateInterface {
            $this->predicate = (new $this->predicate);
            return $this->predicate;
        });
    }
}