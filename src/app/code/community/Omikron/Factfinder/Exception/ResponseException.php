<?php

class Omikron_Factfinder_Exception_ResponseException extends \RuntimeException
{
    /**
     * Omikron_Factfinder_Exception_ResponseException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, \Throwable $previous = null) // phpcs:ignore
    {
        parent::__construct($message ?: 'Response body was empty', $code, $previous);
    }
}
