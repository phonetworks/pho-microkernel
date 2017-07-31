<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Acl;

// 0x07554
class ActorAclTest extends AclTestCase 
{

    public function testActorAclWithSetter() {
        $acl = $this->user->acl();
        $this->go($acl);
    }

    private function go($acl) {
        // creator (self) => e
        // can change info, read and follow!?
        $this->assertFalse($acl->manageable($this->user));
        $this->assertTrue($acl->writeable($this->user));
        $this->assertTrue($acl->readable($this->user));
        $this->assertTrue($acl->executable($this->user));

        // stranger, they are in the same graph  => 5
        // can read and follow
        $this->assertFalse($acl->manageable($this->stranger));
        $this->assertFalse($acl->writeable($this->stranger));
        $this->assertTrue($acl->readable($this->stranger));
        $this->assertTrue($acl->executable($this->stranger));

        // anonymous
        // can read about him, can't follow, needs to become a member first
        $this->assertFalse($acl->manageable($this->anonymous));
        $this->assertFalse($acl->writeable($this->anonymous));
        $this->assertTrue($acl->readable($this->anonymous));
        $this->assertFalse($acl->executable($this->anonymous));

        // setter
        $acl->chmod(0x0f750);

        // someone outside, anonymous
        $this->assertFalse($acl->manageable($this->anonymous));
        $this->assertFalse($acl->writeable($this->anonymous));
        $this->assertFalse($acl->readable($this->anonymous));
        $this->assertFalse($acl->executable($this->anonymous));

        $acl->set("u:".$this->anonymous->id().":", "mrwx");
        $this->assertTrue($acl->manageable($this->anonymous));
        $this->assertTrue($acl->writeable($this->anonymous));
        $this->assertTrue($acl->readable($this->anonymous));
        $this->assertTrue($acl->executable($this->anonymous));

        $acl->del("u:".$this->anonymous->id().":");
        $this->assertFalse($acl->manageable($this->anonymous));
        $this->assertFalse($acl->writeable($this->anonymous));
        $this->assertFalse($acl->readable($this->anonymous));
        $this->assertFalse($acl->executable($this->anonymous));
    }

}