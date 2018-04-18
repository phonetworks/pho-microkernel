<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel;

class FeedTest extends CustomFounderTestCase 
{

    public function testFeedIsAThing() {
        $feed_type = $this->getKernelConfig()["services"]["feed"]["type"];
        if(empty($feed_type))
            $this->markTestSkipped(
                sprintf('Feed service not active: %s', $feed_type)
            );
        $this->flushDBandRestart();
        $feed = $this->kernel->feed();
        $this->assertNotNull($feed);
    }  

    public function testCreateFeed() {
        $feed_type = $this->getKernelConfig()["services"]["feed"]["type"];
        if(empty($feed_type))
            $this->markTestSkipped(
                sprintf('Feed service not active: %s', $feed_type)
            );
        $foundersFeed =  $this->kernel->feed()->feed(
            $this->kernel->founder()->label(), 
            (string) $this->kernel->founder()->id()
        );
        $anotherFeed = $this->kernel->feed()->feed(
            $this->kernel->founder()->label(), 
            "id".rand()
        );
        $activities = $foundersFeed->getActivities(0,10);
        $other_activities = $anotherFeed->getActivities(0, 10);
        $this->assertCount(0, $activities["results"]);
        $this->assertCount(0, $other_activities["results"]);
        $foundersFeed->addActivity([
            "actor"=> (string) $this->kernel->founder()->id(), // actor id
            "verb"=> "like", // edge
            "object"=> rand(0,5), // object id
            // "tweet"=>"Hello world", // custom field
        ]);
        $activities = $foundersFeed->getActivities(0,10);
        $other_activities = $anotherFeed->getActivities(0, 10);
        $this->assertCount(1, $activities["results"]);
        $this->assertCount(0, $other_activities["results"]);
        $anotherFeed->follow(
            $this->kernel->founder()->label(), 
            (string) $this->kernel->founder()->id()
        );
        $activities = $foundersFeed->getActivities(0,10);
        $other_activities = $anotherFeed->getActivities(0, 10);
        $this->assertCount(1, $activities["results"]);
        $this->assertCount(1, $other_activities["results"]);
    }
}