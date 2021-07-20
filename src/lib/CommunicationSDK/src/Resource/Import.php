<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Communication\Resource;

use Psr\Http\Client\ClientExceptionInterface;

interface Import
{
    /**
     * @param string $channel
     * @param string $type
     * @param array  $params
     *
     * @return array
     * @throws ClientExceptionInterface
     */
    public function import(string $channel, string $type, array $params = []): array;

    /**
     * @param string $channel
     *
     * @return bool
     * @throws ClientExceptionInterface
     */
    public function running(string $channel): bool;
}
