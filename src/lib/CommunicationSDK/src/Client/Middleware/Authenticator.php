<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Communication\Client\Middleware;

use DateTime;
use Omikron\FactFinder\Communication\Credentials;
use Psr\Http\Message\RequestInterface;

/**
 * API Authenticator
 *
 * Authenticates requests for 6.x and 7.x FACT-Finder API.
 *
 * @package Omikron\FactFinder\Communication\Middleware
 */
class Authenticator
{
    /** @var Credentials */
    private $credentials;

    public function __construct(Credentials $credentials)
    {
        $this->credentials = $credentials;
    }

    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            return $handler($request->withHeader('Authorization', $this->getAuthToken()), $options);
        };
    }

    private function getAuthToken(): string
    {
        return $this->credentials->getAuthToken(new DateTime());
    }
}
