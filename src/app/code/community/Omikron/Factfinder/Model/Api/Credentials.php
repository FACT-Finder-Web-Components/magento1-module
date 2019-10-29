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

    public function __construct($username, $password, $prefix, $postfix)
    {
        $this->username = $username;
        $this->password = $password;
        $this->prefix   = $prefix;
        $this->postfix  = $postfix;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $timestamp = (int) (microtime(true) * 1000);
        return [
            'timestamp' => $timestamp,
            'username'  => $this->username,
            'password'  => md5($this->prefix . $timestamp . md5($this->password) . $this->postfix), // phpcs:ignore
        ];
    }

    public function __toString()
    {
        return http_build_query($this->toArray());
    }
}
