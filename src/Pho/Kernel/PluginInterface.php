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
 * All plugins must implement this interface.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
interface PluginInterface
{
    /**
     * Returns the name of the plugin
     * 
     * This name is how the plugins would be called by the kernel
     * 
     * e.g. $kernel->plugin("{name}") 
     *
     * @return string
     */
    public function name(): string;

}