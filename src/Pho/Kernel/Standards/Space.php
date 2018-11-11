<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Standards;

use Pho\Kernel\Kernel;

// no need to persist
// because its one and only child is.
// his elements will automatically become a member of this as well
// which would be the only use of this class.
final class Space extends \Pho\Framework\Space implements \Serializable {

    private $kernel;

    public function __construct(Kernel $kernel) {
        $this->kernel = $kernel;
        //$this->on("particle.added", function($node) use ($kernel) {
            // $kernel->logger()->info("Node added %s", $node->id()->toString());
            // $node->persist();
        //});
        // skip modified because the Space is stateless anyway.
    }

    public function label(): string
    {
        return "Space";
    }

    public function persist(): void {}

        public function onEdgeCreated($edge) {
        }
    
        public function onEdgeConnected($edge) { 
        }
    
        public function onDeleting() { 
        }

}