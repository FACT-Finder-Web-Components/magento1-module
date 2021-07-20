<?php

declare(strict_types=1);

namespace spec\Omikron\FactFinder\Communication\Resource\NG;

use Omikron\FactFinder\Communication\Client\ClientInterface;
use Omikron\FactFinder\Communication\Resource\Import;
use PhpSpec\ObjectBehavior;

class ImportAdapterSpec extends ObjectBehavior
{
    public function let(ClientInterface $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_should_have_type_import()
    {
        $this->shouldHaveType(Import::class);
    }
}
