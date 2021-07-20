<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Communication\Client;

use GuzzleHttp\HandlerStack;
use Omikron\FactFinder\Communication\Client\Middleware\Authenticator;
use Omikron\FactFinder\Communication\Client\Middleware\HttpErrors;
use Omikron\FactFinder\Communication\Credentials;
use Omikron\FactFinder\Communication\ServerUrl;
use Omikron\FactFinder\Communication\Version;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ClientBuilder
{
    /** @var string */
    private $serverUrl;

    /** @var Credentials */
    private $credentials;

    /** @var string */
    private $version = Version::NG;

    public function withServerUrl(string $serverUrl): ClientBuilder
    {
        $this->serverUrl = (string) new ServerUrl($serverUrl);
        return $this;
    }

    public function withCredentials(Credentials $credentials): ClientBuilder
    {
        $this->credentials = $credentials;
        return $this;
    }

    public function withVersion(string $version): ClientBuilder
    {
        $this->version = $version;
        return $this;
    }

    public function build(): ClientInterface
    {
        $handler = HandlerStack::create();
        $handler->push(new HttpErrors());

        $config = [
            'base_uri'    => $this->serverUrl,
            'handler'     => $handler,
            'headers'     => ['Accept' => 'application/json'],
            'http_errors' => false,
        ];

        return new Client($this->version === Version::NG ? $this->ngConfig($config) : $this->standardConfig($config));
    }

    private function ngConfig(array $config): array
    {
        return ['auth' => $this->credentials->getAuth()] + $config;
    }

    private function standardConfig(array $config): array
    {
        $config['handler']->push(new Authenticator($this->credentials));
        return $config;
    }
}
