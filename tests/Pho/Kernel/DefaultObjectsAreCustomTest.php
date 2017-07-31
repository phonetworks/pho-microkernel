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

class DefaultObjectsAreCustomTest extends CustomFounderTestCase
{

    public function testSimple() {
        $this->flushDBandRestart();
        $this->assertInstanceOf(Kernel::class, $this->kernel);   
        $this->assertInstanceOf(\PhoNetworksAutogenerated\User::class, $this->kernel->founder());
        $this->assertInstanceOf(\PhoNetworksAutogenerated\Graph::class, $this->kernel->graph());
        $u = new \PhoNetworksAutogenerated\User($this->kernel, $this->graph, "123456");
        $this->created[] = $u->id();
        $post = $u->post("my first post");
        $this->assertInstanceOf(\PhoNetworksAutogenerated\Status::class, $post);
        //eval(\Psy\sh());
    }


}