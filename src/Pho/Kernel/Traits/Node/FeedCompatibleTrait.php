<?php

namespace Pho\Kernel\Traits\Nodes;

use Pho\Kernel\Traits\FeedCompatibleInterface;

/**
 * Nodes that implement this
 * do update the Feed.
 * 
 * @author Emre Sokullu <esokullu@phonetworks.org>
 */
trait FeedCompatibleTrait //  implements FeedCompatibleInterface
{
    protected function generateFeedUpdate(): string
    {
        $template = self::FEED_SIMPLE;
        if(!preg_match_all('/%([^%]+)%/', $template, $matches))
            return $template;
        $processed = [];
        foreach($matches[1] as $match) {
            // behaves differently between Edge and Node
            // function processing
            $processed[] = $this->attributes()->$match;
        }
        return str_replace($matches[1], $processed, $template);
        // [|] to <a href=""></a> // or leave that to js
    }

    public function updateFeed(): string
    {
        if(null!==(self::FEED_SIMPLE)||empty(self::FEED_SIMPLE))
            return "";
        $update = $this->generateFeedUpdate();
        return $update;
        //$this->kernel()->feed()->add($update);
    }
}