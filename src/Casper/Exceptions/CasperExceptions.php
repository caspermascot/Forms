<?php


namespace Casper\Exceptions;


use Exception;

class CasperExceptions extends Exception
{

    /**
     * CasperExceptions constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message,int $code=400)
    {
        parent::__construct($message, $code);
    }
}