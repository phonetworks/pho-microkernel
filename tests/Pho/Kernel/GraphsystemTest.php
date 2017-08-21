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
        $this->kernel->gs()->entity(\Pho\Lib\Graph\ID::generate());
    }  

    public function testNoEdge() {
        $this->expectException(Exceptions\EdgeDoesNotExistException::class);
        $this->kernel->gs()->edge($this->kernel->founder()->id()->toString());
    }  

}