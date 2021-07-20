<?php

declare(strict_types=1);

namespace spec\Omikron\FactFinder\Communication;

use PhpSpec\ObjectBehavior;

class ServerUrlSpec extends ObjectBehavior
{
    public function it_should_validate_the_server_url()
    {
        $this->beConstructedWith('malformed_urk');
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();

        $this->beConstructedWith('https://www.example.com');
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();

        $this->beConstructedWith('https://www.example.com/fact-finder');
        $this->shouldNotThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    public function it_should_append_a_trailing_slashes()
    {
        $this->beConstructedWith('https://www.example.com/fact-finder');
        $this->__toString()->shouldReturn('https://www.example.com/fact-finder/');
    }
}
