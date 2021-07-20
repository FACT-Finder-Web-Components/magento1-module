<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Communication\Client;

use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * API Client
 *
 * Guzzle added support for PSR-18 in v7. By using a wrapper we offer
 * a decoupled approach that also implements PSR-18.
 */
class Client implements ClientInterface
{
    /** @var HttpClient */
    private $client;

    public function __construct(array $config = [])
    {
        $this->client = new HttpClient($config);
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->client->send($request);
    }

    public function request(string $method, $uri, array $options = []): ResponseInterface
    {
        return $this->client->request($method, $uri, $options);
    }
}
