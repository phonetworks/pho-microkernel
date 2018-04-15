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

class FeedTest extends CustomFounderTestCase 
{

    public function testEdgeCounts() {
        $this->flushDBandRestart();
        $feed = $this->kernel->feed();
        $this->assertNotNull($feed);
    }  
}