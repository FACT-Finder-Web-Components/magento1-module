<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Communication\Resource\NG;

use Omikron\FactFinder\Communication\Client\ClientInterface;
use Omikron\FactFinder\Communication\Resource\Search;

class SearchAdapter implements Search
{
    /** @var ClientInterface */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function search(string $channel, string $query, array $params = []): array
    {
        $params   = ['query' => $query] + $params;
        $response = $this->client->request('GET', "rest/v4/search/{$channel}", ['query' => $params]);
        return (array) json_decode((string) $response->getBody(), true);
    }

    public function suggest(string $channel, string $query, array $params = []): array
    {
        $params   = ['query' => $query] + $params;
        $response = $this->client->request('GET', "rest/v4/suggest/{$channel}", ['query' => $params]);
        return (array) json_decode((string) $response->getBody(), true);
    }
}
