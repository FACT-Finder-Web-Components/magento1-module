<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Communication\Resource\NG;

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
        $params   = ['body' => json_encode($eventData), 'headers' => ['Content-Type' => 'application/json']];
        $response = $this->client->request('POST', "rest/v4/track/{$channel}/{$event}", $params);
        return json_decode((string) $response->getBody(), true) ?? [];
    }
}
