<?php


namespace Casper\Exceptions;


class FieldCreateFailedException extends CasperExceptions
{
    const MESSAGE = 'Cannot set required to true when a default is provided, On field %s of form %s ';

    public function __construct(string $field, string $formName)
    {
        parent::__construct(sprintf(self::MESSAGE, $field, $formName), 400);
    }
}