<?php

declare(strict_types=1);

namespace spec\Omikron\FactFinder\Communication\Resource;

use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron\FactFinder\Communication\Client\ClientInterface;
use Omikron\FactFinder\Communication\Resource\NG\ImportAdapter as NgImportAdapter;
use Omikron\FactFinder\Communication\Resource\NG\SearchAdapter as NgSearchAdapter;
use Omikron\FactFinder\Communication\Resource\NG\TrackingAdapter as NgTrackingAdapter;
use Omikron\FactFinder\Communication\Resource\Standard\ImportAdapter as StandardImportAdapter;
use Omikron\FactFinder\Communication\Resource\Standard\SearchAdapter as StandardSearchAdapter;
use Omikron\FactFinder\Communication\Resource\Standard\TrackingAdapter as StandardTrackingAdapter;
use Omikron\FactFinder\Communication\Version;
use PhpSpec\ObjectBehavior;

class AdapterFactorySpec extends ObjectBehavior
{
    public function it_should_construct_ng_adapters(ClientBuilder $clientBuilder, ClientInterface $client)
    {
        $clientBuilder->withVersion(Version::NG)->willReturn($clientBuilder);
        $clientBuilder->build()->willReturn($client);

        $this->beConstructedWith($clientBuilder, Version::NG);
        $this->getImportAdapter()->shouldBeAnInstanceOf(NgImportAdapter::class);
        $this->getSearchAdapter()->shouldBeAnInstanceOf(NgSearchAdapter::class);
        $this->getTrackingAdapter()->shouldBeAnInstanceOf(NgTrackingAdapter::class);
    }

    public function it_should_construct_standard_adapters(ClientBuilder $clientBuilder, ClientInterface $client)
    {
        $clientBuilder->withVersion('7.3')->willReturn($clientBuilder);
        $clientBuilder->build()->willReturn($client);

        $this->beConstructedWith($clientBuilder, '7.3');
        $this->getImportAdapter()->shouldBeAnInstanceOf(StandardImportAdapter::class);
        $this->getSearchAdapter()->shouldBeAnInstanceOf(StandardSearchAdapter::class);
        $this->getTrackingAdapter()->shouldBeAnInstanceOf(StandardTrackingAdapter::class);
    }
}
