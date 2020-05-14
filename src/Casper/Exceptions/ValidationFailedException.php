<?php


namespace Casper\Exceptions;


class ValidationFailedException extends CasperExceptions
{
    public function __construct($message='This field is invalid', int $code=400)
    {
        parent::__construct($message, $code);
    }
}