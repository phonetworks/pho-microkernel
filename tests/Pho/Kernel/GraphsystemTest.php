<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel;

class GraphsystemTest extends TestCase 
{

    public function testNoEntity() {
        $this->expectException(Exceptions\EntityDoesNotExistException::class);
        $this->kernel->gs()->entity(\Pho\Lib\Graph\ID::generate(new \Pho\Lib\Graph\Node($this->graph)));
    }  

    public function testNoEdge() {
        $this->expectException(Exceptions\EdgeDoesNotExistException::class);
        $this->kernel->gs()->edge($this->kernel->founder()->id()->toString());
    }  

    public function testNodeDeletion() {
        $content = $this->kernel->founder()->post("emre sokullu");
        $content_id = (string) $content->id();
        $content->destroy();
        $this->expectException(Exceptions\NodeDoesNotExistException::class);
        $this->kernel->gs()->node($content_id);
    }

    public function testEdgeDeletion() {
        $content = $this->kernel->founder()->post("emre sokullu");
        $edge = $content->edges()->in()->current();
        $edge_id = (string) $edge->id();
        $edge->destroy();
        $this->expectException(Exceptions\EdgeDoesNotExistException::class);
        $this->kernel->gs()->edge($edge_id);
    }

    public function testNodeDeletionsImpactOnEdge() {
        $content = $this->kernel->founder()->post("emre sokullu");
        $content_id = (string) $content->id();
        $edge = $content->edges()->in()->current();
        $edge_id = (string) $edge->id();
        $content->destroy();
        $this->expectException(Exceptions\EdgeDoesNotExistException::class);
        $this->kernel->gs()->edge($edge_id);
    }

    /** 
     * @todo needs to be thought through.
     */
    /*
    public function testEdgeDeletionsImpactOnNode() {
        $content = $this->kernel->founder()->post("emre sokullu");
        $content_id = (string) $content->id();
        $edge = $content->edges()->in()->current();
        $edge_id = (string) $edge->id();
        $edge->destroy();
        $this->expectException(Exceptions\NodeDoesNotExistException::class);
        $this->kernel->gs()->node($content_id);
    }
    */

    /**
     * @todo test subgraphs
     */

}