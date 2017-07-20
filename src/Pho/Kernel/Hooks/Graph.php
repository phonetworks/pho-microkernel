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

use Pho\Lib\Graph\ID;
use Pho\Lib\Graph\EdgeInterface;
use Pho\Lib\Graph\NodeInterface;
use Pho\Lib\Graph\GraphInterface;

/**
 * Compat layer between the kernel and lower level packages
 * 
 * This trait is a compatibility layer between the kernel and 
 * lower level packages, namely pho-lib-graph and pho-framework.
 * Both of these packages provide hydration functions useful to
 * implement persistence at higher levels. This trait is 
 * responsible of implementing these hydration/persistence methods
 * for the kernel.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class Graph
{
    public static function setup(GraphInterface $graph): void
    {
        $graph->hook("add", (function(NodeInterface $node): void {
                $this->persist();
            })
        );

        $graph->hook("remove", (function(NodeInterface $node): void {
                $this->persist();
            })
        );

        $graph->hook("get", (function(ID $node_id): NodeInterface {
                return $this->kernel->gs()->node($node_id);
            })
        );

        $graph->hook("members", (function(): array {
                foreach($this->node_ids as $node_id) {
                    $this->nodes[$node_id] = 
                        $this->kernel->gs()->node($node_id);
                }
                return $this->nodes;
            })
        );
    }
}