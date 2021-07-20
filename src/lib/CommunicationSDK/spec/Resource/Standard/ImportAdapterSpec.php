<?php

declare(strict_types=1);

namespace spec\Omikron\FactFinder\Communication\Resource\Standard;

use Omikron\FactFinder\Communication\Client\ClientInterface;
use Omikron\FactFinder\Communication\Resource\Import;
use PhpSpec\ObjectBehavior;

class ImportAdapterSpec extends ObjectBehavior
{
    public function let(ClientInterface $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_is_an_adapter()
    {
        $this->shouldHaveType(Import::class);
    }
}
