<?php

declare(strict_types=1);

namespace spec\Omikron\FactFinder\Communication;

use Omikron\FactFinder\Communication\Version;
use PhpSpec\ObjectBehavior;

class VersionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Version::class);
    }
}
