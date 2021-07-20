<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Communication\Resource\Standard;

use BadMethodCallException;
use Omikron\FactFinder\Communication\Client\ClientInterface;
use Omikron\FactFinder\Communication\Resource\Import;

class ImportAdapter implements Import
{
    /** @var ClientInterface */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function import(string $channel, string $type, array $params = []): array
    {
        $params = ['channel' => $channel, 'type' => ['search' => 'data'][$type] ?? $type, 'format' => 'json'] + $params;
        $resp   = $this->client->request('GET', 'Import.ff', $params);
        return json_decode((string) $resp->getBody(), true);
    }

    public function running(string $channel): bool
    {
        throw new BadMethodCallException('Not yet implemented');
    }
}
