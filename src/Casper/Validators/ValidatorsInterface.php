<?php


namespace Casper\Validators;


use Casper\Fields\Fields;

interface ValidatorsInterface
{
    /**
     * throws ValidationFailedException
     * returns mixed
     * @param Fields $field
     * @return Fields
     */
    public function run(Fields $field): Fields ;
}