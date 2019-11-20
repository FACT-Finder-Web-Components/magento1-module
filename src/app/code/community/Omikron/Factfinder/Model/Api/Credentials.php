<?php

class Omikron_Factfinder_Model_Api_Credentials
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

    /**
     * @return array
     */
    public function toArray(): array
    {
        $timestamp = (int) (microtime(true) * 1000);
        return [
            'timestamp' => $timestamp,
            'username'  => $this->username,
            'password'  => md5($this->prefix . $timestamp . md5($this->password) . $this->postfix), // phpcs:ignore
        ];
    }

    public function __toString(): string
    {
        return 'Basic ' . base64_encode("$this->username:$this->password");
    }
}
