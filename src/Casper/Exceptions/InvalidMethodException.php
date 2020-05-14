<?php


namespace Casper\Exceptions;


class InvalidMethodException extends CasperExceptions
{
    const MESSAGE = 'Invalid form method given, must be one of get or post';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, 400);
    }
}