<?php

declare(strict_types=1);

namespace spec\Omikron\FactFinder\Communication\Client;

use Omikron\FactFinder\Communication\Client\ClientInterface;
use PhpSpec\ObjectBehavior;
use Psr\Http\Client\ClientInterface as PsrClientInterface;

class ClientSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ClientInterface::class);
        $this->shouldHaveType(PsrClientInterface::class);
    }
}
