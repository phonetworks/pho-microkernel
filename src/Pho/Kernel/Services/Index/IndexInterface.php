<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Services\Index;

use Pho\Lib\Graph\EntityInterface;

/**
 * Index Adapters for Pho Kernel
 * 
 * Indexing makes nodes and edges searchable by their attributes.
 * 
 * @author  Emre Sokullu <emre@phonetworks.org>
 */
interface IndexInterface
{
    /**
     * Indexes an entity.
     * 
     * @param EntityInterface $entity An entity object; node or edge.
     * @param bool $new Whether the object has just been initialized, hence never been indexed before.
     *
     * @return void
     */
    public function index(EntityInterface $entity, bool $new=false): void;

    /**
     * Searches through the index with given key and its value.
     * 
     * @param string $value Value to search
     * @param string $key The key to search for. Optional.
     * @param array $classes The object classes to search for. Optional.
     *
     * @return array
     */
    public function search(string $value, string $key = "", array $classes = array()): array;

}