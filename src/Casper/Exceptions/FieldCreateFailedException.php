<?php


namespace Casper\Exceptions;


class FieldCreateFailedException extends CasperExceptions
{
    public function __construct(string $message)
    {
        parent::__construct($message, 400);
    }
}