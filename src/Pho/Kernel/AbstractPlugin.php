<?php

namespace Pho\Kernel;

abstract class AbstractPlugin implements PluginInterface
{
    public function name(): string
    {
        return substr(get_class($this), 0, -1 * strlen("Plugin"));
    }
}