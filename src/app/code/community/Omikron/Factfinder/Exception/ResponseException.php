<?php

class Omikron_Factfinder_Exception_ResponseException extends RuntimeException
{
    /**
     * @param string    $message
     * @param int       $code
     * @param Throwable $previous
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null) // phpcs:ignore
    {
        parent::__construct($message ?: 'Response body was empty', $code, $previous);
    }
}
