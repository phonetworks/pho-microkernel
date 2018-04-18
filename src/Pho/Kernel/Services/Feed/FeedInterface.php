<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Services\Feed;

use Pho\Lib\Graph\EntityInterface;
use Pho\Kernel\Foundation\ParticleInterface;

interface FeedInterface {
    /**
     * Adds an activity 
     *
     * If the $entity is a Node (Actor)
     * - adds Actor's activity to its own wall
     * Otherwise, if the $entity is an Edge
     * - adds Edge's activity to its tail's wall.
     * 
     * @param EntityInterface $entity
     * 
     * @return void
     */
    public function add(EntityInterface $entity): void;

    /**
     * Undocumented function
     *
     * @param ParticleInterface $entity
     * @return void
     */
    public function follow(ParticleInterface $subject, ParticleInterface $object): void;
}
