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

use Pho\Lib\Graph;
use Pho\Framework;
use Pho\Lib\Graph\ID;

class ActorAcl extends AbstractAcl implements AclInterface {

/*
    protected function hasSubscriber(Framework\Actor $actor): bool
    {
        $members = $this->core->getSubscribers();
        foreach($members as $member) {
            if($member->id()->equals($actor->id())) {
                return true;
             }
        }
        return false;
    }
    */

    public function isSubscriber(Framework\Actor $actor): bool
    {
        return
            (
                in_array(Pho\Framework\ActorOut\Subscribe::class, $this->core->getRegisteredIncomingEdges()) 
                && $this->core->hasSubscriber($actor->id())
            );
    }


}