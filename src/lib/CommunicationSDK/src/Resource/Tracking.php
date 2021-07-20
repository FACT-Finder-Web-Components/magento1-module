<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Communication\Resource;

use Psr\Http\Client\ClientExceptionInterface;

interface Tracking
{
    /**
     * @param string $channel
     * @param string $event
     * @param array  $eventData
     *
     * @return array
     * @throws ClientExceptionInterface
     */
    public function track(string $channel, string $event, array $eventData = []): array;
}
