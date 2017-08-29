<?php

/**
 * This file is part of the Phở package.
 * 
 * (c) Emre Sokullu <emre@phonetworks.org> 
 *
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Hooks;

use Pho\Lib\Graph;

/**
 * {@inheritDoc}
 */
class Edge implements HookInterface
{
    /**
     * {@inheritDoc}
     */
    public static function setup(/*Graph\EdgeInterface*/ $edge): void
    {
        $edge->hook("head", (function(): Graph\NodeInterface  {
                $this->head = new \Pho\Lib\Graph\HeadNode();
                $this->head->set($this->injection("kernel")->gs()->node($this->head_id));
                return $this->head;
            })
        );

        $edge->hook("tail", (function(): Graph\NodeInterface {
                $this->tail =  new \Pho\Lib\Graph\TailNode();
                $this->tail->set($this->injection("kernel")->gs()->node($this->tail_id));
                return $this->tail;
            })
        );
        $edge->hook("predicate", (function(): Graph\PredicateInterface {
                $this->predicate = (new $this->predicate);
                return $this->predicate;
            })
        );
    }
}
