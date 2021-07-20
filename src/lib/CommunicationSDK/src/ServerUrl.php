<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Communication;

use InvalidArgumentException;

class ServerUrl
{
    /** @var string */
    private $serverUrl;

    public function __construct(string $serverUrl)
    {
        if (!filter_var($serverUrl, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
            throw new InvalidArgumentException('Please provide a valid FACT-Finder URL');
        }
        $this->serverUrl = rtrim($serverUrl, '/') . '/';
    }

    public function __toString()
    {
        return $this->serverUrl;
    }
}
