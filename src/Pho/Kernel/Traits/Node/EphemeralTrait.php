<?php

namespace Pho\Kernel\Traits\Node;

/**
 * Ephemeral Trait
 * 
 * Ephemeral nodes die out in a certain specified time. In
 * other words, they expire, and auto-delete.
 * 
 * Most famous example of ephemeral social objects is Snapchat's
 * video messages, which are set to disappear within 24 hours.
 * 
 * Any node that uses this trait, must define a LIFETIME constant at
 * the top of the node class.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
trait EphemeralTrait
{
    public function loadNodeTrait(Kernel $kernel): void
    {
        $this->kernel = $kernel;
        $this->acl = Acl\AclFactory::seed($kernel, $this, self::DEFAULT_MOD);
        $this->persist($this->loadEditorsFrame());
        $this->expire();
    }

    protected function expire(): void
    {
        if(static::T_EXPIRATION>0)
            $this->kernel->gs()->expire($this->id(), self::LIFETIME);
    }
}