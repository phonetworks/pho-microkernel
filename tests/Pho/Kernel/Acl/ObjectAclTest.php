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

// 0x07555
class ObjectAclTest extends AclTestCase 
{

    public function testObjectAclWithSetter() {
        $this->post = $this->user->post("my first post");
        $acl = $this->post->acl();
        $this->go($acl, $this->post);
    }

    private function go($acl, $content) {
        // creator
        $this->assertFalse($acl->manageable($this->user), 
            "actor's role is: ".$acl->resolveRole($this->user).
            " .. actor is: ".$this->user->id().
            " .. creator is: ".$content->creator()->id());
        $this->assertTrue($acl->writeable($this->user));
        $this->assertTrue($acl->readable($this->user));
        $this->assertTrue($acl->executable($this->user));

        // stranger, they are in the same graph though.
        // 5
        $this->assertFalse($acl->manageable($this->stranger));
        $this->assertFalse($acl->writeable($this->stranger));
        //eval(\Psy\sh());
        $this->assertTrue($acl->readable($this->stranger), 
            "actor's role is: ".$acl->resolveRole($this->stranger)
            ." .. actor is: ".$this->stranger->id()
            ." .. actor's context is: ".$this->stranger->context()->id()
            ." .. object's context is: ".$content->context()->id()
            ." .. does object's context contain actor? ".  ($content->context()->contains($this->stranger->id()) ? "Y" : "N")
        );
        $this->assertTrue($acl->executable($this->stranger)); // react

        // 
        $this->assertFalse($acl->manageable($this->anonymous));
        $this->assertFalse($acl->writeable($this->anonymous));
        $this->assertTrue($acl->readable($this->anonymous));
        $this->assertTrue($acl->executable($this->anonymous));
    }

}