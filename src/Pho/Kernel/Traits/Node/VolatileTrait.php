<?php

namespace Pho\Kernel\Node;

trait VolatileTrait  {

    use PersistentTrait;

    public function persist(bool $skip = false): void {}

    public function destroy(): void {}

}