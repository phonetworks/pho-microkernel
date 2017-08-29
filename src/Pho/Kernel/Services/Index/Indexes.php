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

use Pho\Kernel\Services\ServiceInterface;
use Pho\Kernel\Services\Index\Adapters;

/**
 * Provides Index methods to the Events adapter classes.
 */
class Index implements IndexInterface, ServiceInterface
{
    private $db;

    /**
   * Constructor.
   */
    public function __construct(string $dbtype = '', array $params = [])
    {
        switch ($dbtype) {
            default:
                $this->db = new Elasticsearch($params);
                break;
        }
    }

    /**
     * Indexes an entity.
     *
     * @param EntityInterface $entity An entity object; node or edge.
     * @param bool $new Whether the object has just been initialized, hence never been indexed before.
     *
     * @return void
     */
    public function index(EntityInterface $entity, bool $new = false): void
    {
        $classes = get_class($entity) + class_parents($entity);
        if ($new) {
            $this->db->addToIndex($entity->id(), $entity->attributes()->toArray(), $classes);
        } else {
            $this->db->editInIndex($entity->id(), $entity->attributes()->toArray(), $classes);
        }
    }

    /**
     * Searches through the index with given key and its value.
     *
     * @param string $value Value to search
     * @param string $key The key to search for. Optional.
     * @param array $classes The object classes to search for. Optional.
     *
     * @return array
     */
    public function search(string $value, string $key = "", array $classes = array()): array
    {
        $founded = $this->db->searchInIndex($value, $key, $classes);

        $return = [];
        foreach ($founded as $entitys) {
            $return[] = $entitys['id'];
        }
        
        return $return;
    }

    /**
     * Searches through the index with given key and its value.
     *
     * Returns the entity IDs as string
     * 
     * @param string $value Value to search
     * @param string $key The key to search for. Optional.
     * @param array $classes The object classes to search for. Optional.
     *
     * @return array Entity IDs (in string format) in question
     */
    public function searchFlat(string $value, string $key = "", array $classes = array()): array;

}
