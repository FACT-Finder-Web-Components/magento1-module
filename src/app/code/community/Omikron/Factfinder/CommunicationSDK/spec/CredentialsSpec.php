<?php

declare(strict_types=1);

namespace spec\Omikron\FactFinder\Communication;

use PhpSpec\ObjectBehavior;

class CredentialsSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('user', 'userpw', 'FACT-FINDER', 'FACT-FINDER');
    }

    public function it_should_return_an_auth_array()
    {
        $this->getAuth()->shouldReturn(['user', 'userpw']);
    }

    public function it_should_correctly_form_an_authentication_token()
    {
        $dateTime = new \DateTime('2010-04-08 13:22:33.523000');
        $this->getAuthToken($dateTime)->shouldReturn('user:167539c3e7aba8388eee252f429a4a1a:1270732953523');
    }
}
