<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Communication\Resource\Standard;

use Omikron\FactFinder\Communication\Client\ClientInterface;
use Omikron\FactFinder\Communication\Resource\Tracking;

class TrackingAdapter implements Tracking
{
    /** @var ClientInterface */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function track(string $channel, string $event, array $eventData = []): array
    {
        $payload  = implode('&', array_map('http_build_query', $eventData));
        $query    = http_build_query(['event' => $event, 'channel' => $channel]);
        $response = $this->client->request('GET', "Tracking.ff?{$query}&{$payload}");
        return json_decode((string) $response->getBody(), true) ?? [];
    }
}
