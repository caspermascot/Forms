<?php


namespace Casper\Exceptions;


class InvalidUrlException extends CasperExceptions
{
    public const MESSAGE = 'Invalid form url given';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}