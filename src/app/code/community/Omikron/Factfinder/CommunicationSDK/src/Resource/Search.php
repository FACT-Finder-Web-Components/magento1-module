<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Communication\Resource;

use Psr\Http\Client\ClientExceptionInterface;

interface Search
{
    /**
     * @param string $channel
     * @param string $query
     * @param array  $params
     *
     * @return array
     * @throws ClientExceptionInterface
     */
    public function search(string $channel, string $query, array $params = []): array;

    /**
     * @param string $channel
     * @param string $query
     * @param array  $params
     *
     * @return array
     * @throws ClientExceptionInterface
     */
    public function suggest(string $channel, string $query, array $params = []): array;
}
