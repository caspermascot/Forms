<?php


namespace Casper\Exceptions;


class InvalidButtonTypeException extends CasperExceptions
{
    const MESSAGE = 'Invalid button type given, must be one of submit or reset';

    public function __construct()
{
    parent::__construct(self::MESSAGE, 400);
}
}