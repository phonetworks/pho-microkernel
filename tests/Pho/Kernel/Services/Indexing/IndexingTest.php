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

use Elasticsearch\ClientBuilder;
use Pho\Kernel\Kernel;
use Pho\Kernel\Services\Index;
use Pho\Kernel\TestCase;

class IndexTest extends TestCase
{

    protected $client;

    public function setUp()
    {
        putenv('INDEX_DB=phonetworkstest');
        putenv('INDEX_TABLE=phoindex');

        parent::setUp();

        $client       = new \Elasticsearch\ClientBuilder();
        $this->client = $client->build();

        $indexParams          = [];
        $indexParams['index'] = getenv("INDEX_DB");
        if ($this->client->indices()->exists($indexParams)) {
            $this->client->indices()->delete($indexParams);
        }

        $this->client->indices()->create($indexParams);

        $this->client = $client->build();
    }

    public function tearDown()
    {
        $this->client->indices()->delete(['index' => getenv('INDEX_DB')]);
    }

    public function testCreatedIndex()
    {
        $this->assertTrue($this->kernel->index()->client->indices()->exists(['index' => getenv('INDEX_DB')]));
    }

    public function testSearchIndex()
    {
        $index    = $this->kernel->index();
        $document =
            [
            "id"      => "b31c8f9e-a476-4b5d-ad53-36d44c9ed615",
            "attr"    => [
                [
                    "k" => "Password",
                    "v" => "e10adc3949ba59abbe56e057f20f883e",
                ],
                [
                    "k" => "JoinTime",
                    "v" => "1504210623",
                ],
                [
                    "k" => "Birthday",
                    "v" => "411436800",
                ],
                [
                    "k" => "About",
                    "v" => "",
                ],
                [
                    "k" => "Key",
                    "v" => "Value",
                ],
            ],
            "classes" => [
                "PhoNetworksAutogenerated\\User"           => "PhoNetworksAutogenerated\\User",
                "Pho\\Kernel\\Foundation\\AbstractActorDP" => "Pho\\Kernel\\Foundation\\AbstractActorDP",
                "Pho\\Kernel\\Foundation\\AbstractActor"   => "Pho\\Kernel\\Foundation\\AbstractActor",
                "Pho\\Framework\\Actor"                    => "Pho\\Framework\\Actor",
                "Pho\\Lib\\Graph\\Node"                    => "Pho\\Lib\\Graph\\Node",
            ],
        ];

        $params   = ['index' => getenv('INDEX_DB'), 'type' => 'User', "id" => "b31c8f9e-a476-4b5d-ad53-36d44c9ed615", 'body' => $document];
        $response = $this->client->index($params);
        sleep(1);
        if (isset($response['created']) && $response['created']) {
            $params = ['index' => getenv('INDEX_DB'), 'type' => 'User', "id" => "b31c8f9e-a476-4b5d-ad53-36d44c9ed615"];

            $output = $index->search('Value');
            $this->assertSame($output, ['b31c8f9e-a476-4b5d-ad53-36d44c9ed615']);
        } else {
            $this->markTestSkipped('Elasticsearch create document fail');
        }
    }

    public function testAppendIndex()
    {
        $index                   = $this->kernel->index();
        $node                    = new \PhoNetworksAutogenerated\User($this->kernel, $this->kernel->graph(), "123456");
        $node->attributes()->Key = 'Value';

        //$index->index($node, true);

        sleep(1);
        $params = ['index' => getenv('INDEX_DB'), 'body' => ['query' => ['match' => ['id' => $node->id()->toString()]]]];

        $return = $this->client->search($params);
        $this->assertNotEmpty($return['hits']['hits']);
        $this->assertArrayHasKey(4, $return['hits']['hits'][0]['_source']['attr']);
        $this->assertArrayHasKey('v', $return['hits']['hits'][0]['_source']['attr'][4]);
        $this->assertSame($node->attributes()->Key, $return['hits']['hits'][0]['_source']['attr'][4]['v']);
    }

    public function testEditIndex()
    {
        $index                   = $this->kernel->index();
        $node                    = new \PhoNetworksAutogenerated\User($this->kernel, $this->kernel->graph(), "123456");
        $node->attributes()->Key = 'Value';

        //$index->index($node, true);
        sleep(1);
        $node->attributes()->Key = 'Some new value';
        //$index->index($node);
        sleep(1);
        $params = ['index' => getenv('INDEX_DB'), 'type' => $index->getTypeFromClass(get_class($node)), 'id' => $node->id()->toString()];
        try {
            $return = $this->client->get($params);
        } catch (\Elasticsearch\Common\Exceptions\Missing404Exception $e)
        {
            $this->fail('Index not woking properly. Index node do not added to Elasticsearch');
        }

        $this->assertTrue($return['found']);
        $this->assertArrayHasKey(4, $return['_source']['attr']);
        $this->assertArrayHasKey('v', $return['_source']['attr'][4]);
        $this->assertSame($node->attributes()->Key, $return['_source']['attr'][4]['v']);
    }

    public function testSearchByKeyAndValue()
    {
        $index                   = $this->kernel->index();
        $node                    = new \PhoNetworksAutogenerated\User($this->kernel, $this->kernel->graph(), "123456");
        $node->attributes()->Key = 'Value';

        //$index->index($node, true);

        sleep(1);

        $return = $index->search($node->attributes()->Key, 'Key');

        $this->assertNotEmpty($return);
        $this->assertSame($node->id()->toString(), $return[0]);
    }

    public function testSearchByKeyAndClass()
    {
        $index                   = $this->kernel->index();
        $node                    = new \PhoNetworksAutogenerated\User($this->kernel, $this->kernel->graph(), "123456");
        $node->attributes()->Key = 'Value';
        $class                   = $index->getTypeFromClass(get_class($node));

        $index->index($node, true);

        sleep(1);

        $return = $index->search($node->attributes()->Key, null, [$class]);

        $this->assertNotEmpty($return);
        $this->assertSame($node->id()->toString(), $return[0]);
    }

}