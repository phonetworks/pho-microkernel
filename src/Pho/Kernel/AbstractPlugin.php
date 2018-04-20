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

/**
 * An abstract plugin class
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
abstract class AbstractPlugin implements PluginInterface
{
    protected $kernel;

    /**
     * Constructor
     *
     * @param Kernel $kernel Pho Kernel
     */
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * This abstract implementation does nothing
     * but satisfies the specs.
     * 
     * @return void
     */
    public function init(): void
    {

    }

    public function name(): string
    {
        return strtolower(substr(get_class($this), 0, -1 * strlen("Plugin")));
    }
}