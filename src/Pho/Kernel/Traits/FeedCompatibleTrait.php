<?php

namespace Pho\Kernel\Traits;

class FeedCompatibleTrait
{

    protected function processFeedUpdate(string $feed): string
    {
        if(!preg_match_all('/%([^%]+)%/', $feed, $matches))
            return $feed;
        $processed = [];
        foreach($matches[1] as $match) {
            // behaves differently between Edge and Node
            // function processing
            $processed[] = $this->attributes()->$match;
        }
        return str_replace($matches[1], $processed, $feed);
        // [|] to <a href=""></a> // or leave that to js
    }

    public function updateFeed(): string
    {
        // fetches different variables
        // and sends them to proceedFeedUpdate
    }
}