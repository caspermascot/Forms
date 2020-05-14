<?php


namespace Casper\Exceptions;


class InvalidUrlException extends CasperExceptions
{
    const MESSAGE = 'Invalid form url given';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, 400);
    }
}