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
 * Index Query Results
 * 
 * Currently based on Ne4j standards and not very restrictive.
 * E.g. the variables can be set globally.
 * 
 * @todo Make this class more restrictive.
 * @todo Add more indexing options.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class QueryResult 
{
    /**
     * Holds results in a multi-dimensional array
     * 
     * In the form of:
     * 
     * * [0]["value1"]
     * * [0]["value2"]
     * * [1]["value1"]
     * * [1]["value2"]
     * * ...
     *
     * @var array
     */
    protected $results = [];

    /**
     * Holds result summmary in an array
     * 
     * Currently supported properties:
     * * nodesCreated
     * * nodesDeleted
     * * edgesCreated
     * * edgesDeleted
     * * propertiesSet
     * * containsUpdates
     *
     * @var array
     */
    protected $summary = array(
        "nodesCreated" => 0,
        "nodesDeleted" => 0,
        "edgesCreated" => 0,
        "edgesDeleted" => 0,
        "propertiesSet" => 0,
        "containsUpdates" => 0
    );

    public function results(): array
    {
        return $this->results;
    }

    public function summary(): array
    {
        return $this->summary;
    }
    
    public function __call(string $name , array $arguments )/*: mixed*/
    {
        $func = call_user_func_array([$this, $name], $arguments);
        return $func;
    }

}
