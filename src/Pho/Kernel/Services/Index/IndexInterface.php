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
 * Indexing is registered at construction and takes place via kernel event listeners.
 * Currently openCypher language is supported to query the index.
 * 
 * @author  Emre Sokullu <emre@phonetworks.org>
 */
interface IndexInterface
{
    /**
     * Direct access to the index service to query data.
     * 
     * @param string $query Cypher query.
     * @param array $params Query parameters.
     *
     * @return mixed
     */
    public function query(string $query, array $params = []); //: void;

    public function index(\Pho\Lib\Graph\EntityInterface $entity): void;

    public function nodeDeleted(string $id): void;

    public function edgeDeleted(string $id): void;

}
