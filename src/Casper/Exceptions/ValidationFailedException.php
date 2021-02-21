<?php


namespace Casper\Exceptions;


class ValidationFailedException extends CasperExceptions
{
    public const MESSAGE = 'This field is invalid';
    public function __construct($message=self::MESSAGE, int $code=400)
    {
        parent::__construct($message, $code);
    }
}