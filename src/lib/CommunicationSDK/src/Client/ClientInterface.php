<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Communication\Client;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

interface ClientInterface extends \Psr\Http\Client\ClientInterface
{
    /**
     * Create and send an HTTP request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well.
     *
     * @param string              $method  HTTP method.
     * @param string|UriInterface $uri     URI object or string.
     * @param array               $options Request options to apply.
     *
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function request(string $method, $uri, array $options = []): ResponseInterface;
}
