<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Services\Index\Adapters;

use Elasticsearch\ClientBuilder;

/**
 * File based logging. The log files specified at the kernel
 * constructor level or via the *configure* function of the kernel.
 *
 * @author Emre Sokullu
 */
class Elasticsearch implements IndexDbInterface
{
    private $client;
    private $dbname    = 'phonetworks';
    private $tablename = 'indexes';

    public function __construct(array $params = [])
    {
        $this->client = ClientBuilder::create();
        if (isset($params['hosts'])) {
            $this->client->setHosts($params['hosts']);
            $this->client->build();
        }
    }

    /**
     * Bulk insert params of entity to the index
     * @param string $id     uuid of entity
     * @param array  $params array of params with key => value structure (toArray() method)
     * @param array  $classes classes of the current entity
     */
    public function addToIndex(string $id, array $params, array $classes = []): void
    {

        $body = ['attr' => [], 'classes' => $classes, 'id' => $id];
        foreach ($params as $key => $value) {
            $body['attr'][] = ['k' => $key, 'v' => $value];
        }

        $this->client->insert($this->createQuery($id, $body));
    }

    /**
     * Updating existing, remove unused and append new entity attributes to the Indexing DB
     * @param  string $id      uuid/id of entity
     * @param  array  $results array of attributes with key => value structure (toArray() method)
     * @param array  $classes classes of the current entity
     */
    public function editInIndex(string $id, array $params, array $clasess = []): void
    {

        $body = ['attr' => [], 'classes' => $classes, 'id' => $id];
        foreach ($params as $key => $value) {
            $body['attr'][] = ['k' => $key, 'v' => $value];
        }

        $params = $this->createQuery($id, $body);

        $this->db->update($params);
    }

    /**
     * Search in indexing DB all attributes of entity by its ID
     * @param  string $id uuid string
     * @return array     array with keys id, key, value
     */
    public function searchById(string $id): array
    {
        $params = $this->createQuery($id, []);
        unset($params['body']);
        $results = $client->get($params);
        return $this->remapReturn($results);
    }

    /**
     * Search by some params
     * @param  string $id uuid string
     * @return array     array with keys id, key, value
     */
    public function searchInIndex(string $value, string $key = "", array $classes = array()): array
    {
        $query                             = ['query' => []];
        $query['query']['match']['attr.v'] = $value;
        if (!empty($key)) {
            $query['query']['match']['attr.k'] = $key;
        }
        if (!empty($classes)) {
            //$query['query']['match']['classes'] = $classes; //Search by classes not ready yet
         }

        $params = $this->createQuery(null, $query);

        $results = $client->search($params);
        return $this->getIdsList($this->remapReturn($results));
    }

    public function remapReturn(array $results)
    {
        $return = array();
        if (isset($return['hits']) && isset($return['hits']['hits'])) {
            foreach ($return['hits']['hits'] as $founded) {
                $return = [
                    'id'         => $founded['id'],
                    'attributes' => $founded['_source']['attr'],
                    'classes'    => $founded['_source']['classes'],
                ];
            }
        }
        return $return;
    }

    public function getIdsList($lit)
    {
        $ids = [];
        foreach ($founded as $entitys) {
            $ids[] = $entitys['id'];
        }

        return $ids;
    }

    public function createQuery(string $id = null, array $where = []): array
    {
        $query = [
            'index' => $this->dbname,
            'type'  => $this->tablename,
            'body'  => $where,
        ];

        if (!is_null($id)) {
            $query['id'] = $id;
        }

        return $query;
    }

}
