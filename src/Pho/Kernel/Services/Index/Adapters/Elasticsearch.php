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

use Pho\Kernel\Kernel;
use Pho\Kernel\Services\Index\Index;
use Pho\Kernel\Services\ServiceInterface;
use Elasticsearch\ClientBuilder;

/**
 * File based logging. The log files specified at the kernel
 * constructor level or via the *configure* function of the kernel.
 *
 * @author Emre Sokullu
 */
class Elasticsearch extends Index
{
    private $kernel;
    public  $client;
    private $dbname    = 'phonetworks';
    private $tablename = 'phoindex';

    public function __construct(Kernel $kernel, array $params = [])
    {

        $this->kernel = $kernel;
        $this->dbname = getenv('INDEX_DB')?: $this->dbname;
        $this->tablename = getenv('INDEX_TABLE')?: $this->tablename;

        $host         = [$params['host'] ?: getenv('INDEX_URL') ?: 'http://127.0.0.1:9200/'];
        $client = new \Elasticsearch\ClientBuilder($host);
        $this->client = $client->build();
        $indexParams['index'] = $this->dbname;
        if ($this->client->indices()->exists($indexParams)) {
            $this->client->indices()->delete($indexParams);
        }
        if ( ! $this->client->indices()->exists($indexParams)) {
            $params = [
                'index' => $this->dbname,
                'body' => [
                    'mappings' => [
                        $this->tablename => [
                            '_source' => [
                                'enabled' => true
                            ],
                            'properties' =>
                            [
                                'id' => ['type' => 'string'],
                                'attr' => 
                                [
                                    'properties' =>
                                    [
                                        'k' => ['type' => 'string'],
                                        'v' => ['type' => 'string']
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            $this->client->indices()->create($params);
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

        $this->client->index($this->createQuery($id, $body));
    }

    /**
     * Updating existing entity attributes to the Indexing DB
     * @param  string $id      uuid/id of entity
     * @param  array  $results array of attributes with key => value structure (toArray() method)
     * @param array  $classes classes of the current entity
     */
    public function editInIndex(string $id, array $params, array $classes = []): void
    {
        //If node not founded in index - add it.
        if ( ! empty($this->searchById($id))) {
            $this->removeById($id);
        }

        //Update document if node are exists
        $body = ['attr' => [], 'classes' => $classes];
        foreach ($params as $key => $value) {
            $body['attr'][] = ['k' => $key, 'v' => $value];
        }

        $params = $this->createQuery($id, $body);
        $this->client->index($params);
    }

    /**
     * Search in indexing DB all attributes of entity by its ID
     * @param  string $id uuid string
     * @return array     array with keys id, key, value
     */
    public function searchById(string $id): array
    {
        try {
            $results = $this->client->get($this->createQuery($id, false));
        } catch (Elasticsearch\Common\Exceptions\TransportException $e)
        {
            return false;
        }
        return $this->remapReturn($results);
    }

    /**
     * Search in indexing DB all attributes of entity by its ID
     * @param  string $id uuid string
     * @return array     array with keys id, key, value
     */
    public function removeById(string $id): array
    {
        return $this->client->delete($this->createQuery($id, false));
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
        unset($params['type']);
        $results = $this->client->search($params);

        return $this->getIdsList($this->remapReturn($results));
    }

    public function remapReturn(array $results)
    {
        $return = array();
        if (isset($results['hits']) && isset($results['hits']['hits'])) {
            foreach ($results['hits']['hits'] as $founded) {
                $results = [
                    'id'         => $founded['id'],
                    'attributes' => [],
                    'classes'    => $founded['_source']['classes'],
                ];
                foreach ($founded['_source']['attr'] as $attribute) {
                    $results['attributes'][$attribute['k']] = $attribute['v']; 
                }
            }
        } else if (isset($results['_id']) && isset($results['_source'])) {
            $return['id'] = $results['_source']['id'];
            $return['classes'] = $results['_source']['classes'];
            foreach ($results['_source']['attr'] as $attribute) {
                $results['attributes'][$attribute['k']] = $attribute['v']; 
            }   
        }
        return $return;
    }

    public function getIdsList($founded)
    {
        $ids = [];
        foreach ($founded as $entitys) {
            $ids[] = $entitys['id'];
        }

        return $ids;
    }

    public function createQuery(string $id = null, $where = []): array
    {
        $query = [
            'index' => $this->dbname,
            'type'  => $this->tablename,
        ];

        if ($where !== false)
        {
            $query['body'] = $where;
        }

        if (!is_null($id)) {
            $query['id'] = $id;
        }

        return $query;
    }

}
