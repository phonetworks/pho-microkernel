<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Foundation\ActorOut;

use Pho\Kernel\Kernel;
use Pho\Lib\Graph\NodeInterface;
use Pho\Lib\Graph\PredicateInterface;

/**
 * {@inheritDoc}
 */
class Write extends \Pho\Framework\ActorOut\Write
{
    public function __construct(Kernel $kernel, NodeInterface $tail, NodeInterface $head, ?PredicateInterface $predicate = null) 
    {
        parent::__construct($tail, $head, $predicate);
    }
}