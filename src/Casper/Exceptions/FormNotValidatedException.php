<?php


namespace Casper\Exceptions;


class FormNotValidatedException extends CasperExceptions
{
    public const MESSAGE = 'Cannot access method. Needs to call isValid() before properties can be accessed';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}