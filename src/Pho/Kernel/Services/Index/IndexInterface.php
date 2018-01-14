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
     * Searches through the index with given key and its value.
     *
     * @param string $query Cypher query
     * @param array $param Query params. Optional.
     *
     * @return QueryResult Result set
     */
    public function query(string $query, array $params = []): QueryResult;

    /**
     * Direct access to the the index client
     * 
     * This class does also provide direct read-only access to the 
     * client, for debugging purposes.
     *
     * @return mixed Client in its native form
     */
    public function client(); //: mixed

    /**
     * Indexes an entity
     *
     * Given in array form, indexes the entity (be it an 
     * edge or node) making it searchable.
     * 
     * @param array $entity In array form.
     * 
     * @return void
     * 
     * @throws Exception if the entity header is not recognized.
     */
    public function index(array $entity): void;

    /**
     * Notifies the index that a node was deleted
     * 
     * Removes the entity from the index, cleaning 
     * up resources and making index results more efficient.
     *
     * @param string $id ID in string format.
     * 
     * @return void
     */
    public function nodeDeleted(string $id): void;

    /**
     * Notifies the index that an edge was deleted
     * 
     * Removes the entity from the index, cleaning 
     * up resources and making index results more efficient.
     *
     * @param string $id ID in string format.
     * 
     * @return void
     */
    public function edgeDeleted(string $id): void;

    /**
     * Cleans up the index database
     *
     * @return void
     */
    public function flush(): void;

}
