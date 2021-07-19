<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Communication;

use DateTime;

class Credentials
{
    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string */
    private $prefix;

    /** @var string */
    private $postfix;

    public function __construct(string $username, string $password, string $prefix = '', string $postfix = '')
    {
        $this->username = $username;
        $this->password = $password;
        $this->prefix   = $prefix;
        $this->postfix  = $postfix;
    }

    public function getAuth(): array
    {
        return [$this->username, $this->password];
    }

    public function getAuthToken(DateTime $dateTime): string
    {
        $timestamp = $dateTime->format('Uv');
        $password  = md5($this->prefix . $timestamp . md5($this->password) . $this->postfix);
        return "{$this->username}:{$password}:{$timestamp}";
    }
}
