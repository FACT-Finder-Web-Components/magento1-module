<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Communication\Client\Middleware;

use GuzzleHttp\Exception\RequestException;
use Omikron\FactFinder\Communication\Client\ClientException;
use Psr\Http\Message\ResponseInterface;

/**
 * HttpErrors Middleware
 *
 * Ensure PSR-18 compatibility by wrapping Guzzle exceptions.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class HttpErrors
{
    public function __invoke(callable $handler): callable
    {
        return function ($request, array $options) use ($handler) {
            return $handler($request, $options)->then(function (ResponseInterface $response) use ($request) {
                if ($response->getStatusCode() < 400) {
                    return $response;
                }

                $e = RequestException::create($request, $response);
                throw new ClientException($e->getMessage(), $e->getCode(), $e);
            });
        };
    }
}
