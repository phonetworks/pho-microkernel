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

/**
 * Index Adapters for Pho Kernel
 *
 * Indexing makes nodes and edges searchable by their attributes.
 *
 * @author  Emre Sokullu <emre@phonetworks.org>
 */
interface IndexDbInterface
{

    /**
     * Bulk insert params of entity to the index
     * @param string $id     uuid of entity
     * @param array  $params array of params with key => value structure (toArray() method)
     * @param array  $classes classes of the current entity
     */
    public function addToIndex(string $id, array $params, array $classes = []): void;

    /**
     * Updating existing entity attributes to the Indexing DB
     * @param  string $id      uuid/id of entity
     * @param  array  $results array of attributes with key => value structure (toArray() method)
     * @param array  $classes classes of the current entity
     */
    public function editInIndex(string $id, array $params, array $clasess = []): void;

    /**
     * Search in indexing DB all attributes of entity by its ID
     * @param  string $id uuid string
     * @return array     array with keys id, key, value
     */
    public function searchById(string $id): array;

    /**
     * Search by some params
     * @param  string $id uuid string
     * @return array     array with keys id, key, value
     */
    public function searchInIndex(string $value, string $key = "", array $classes = array()): array;
}
