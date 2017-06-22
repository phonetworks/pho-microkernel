<?php

namespace Pho\Kernel\Node;

/**
 * Volatile Trait
 * 
 * Volatile nodes are not persistent, which means, they 
 * are lost once the kernel shuts down.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
trait VolatileTrait  {

    use PersistentTrait;

    public function persist(bool $skip = false): void {}

    public function destroy(): void {}

}