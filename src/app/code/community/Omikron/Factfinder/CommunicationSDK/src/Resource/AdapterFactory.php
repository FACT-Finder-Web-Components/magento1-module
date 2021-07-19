<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Communication\Resource;

use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron\FactFinder\Communication\Client\ClientInterface;
use Omikron\FactFinder\Communication\Version;

class AdapterFactory
{
    /** @var ClientBuilder */
    private $clientBuilder;

    /** @var string */
    private $version;

    public function __construct(ClientBuilder $clientBuilder, string $version = Version::NG)
    {
        $this->clientBuilder = $clientBuilder;
        $this->version       = $version;
    }

    public function getImportAdapter(): Import
    {
        $class = $this->getAdapterClass(Import::class, $this->version);
        return new $class($this->getClient());
    }

    public function getSearchAdapter(): Search
    {
        $class = $this->getAdapterClass(Search::class, $this->version);
        return new $class($this->getClient());
    }

    public function getTrackingAdapter(): Tracking
    {
        $class = $this->getAdapterClass(Tracking::class, $this->version);
        return new $class($this->getClient());
    }

    private function getAdapterClass(string $resource, string $version): string
    {
        $type = $version === Version::NG ? 'NG' : 'Standard';
        return preg_replace('#(\\\[a-z]+)$#i', "\\{$type}$1Adapter", $resource);
    }

    private function getClient(): ClientInterface
    {
        return $this->clientBuilder->withVersion($this->version)->build();
    }
}
