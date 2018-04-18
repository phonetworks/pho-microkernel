<?php

namespace Pho\Kernel\Traits;

interface FeedCompatibleInterface
{
    /**
     * Communicated with the Feed server
     * and generates a feed update.
     *
     * @return void
     */
    public function updateFeed(): string;
}