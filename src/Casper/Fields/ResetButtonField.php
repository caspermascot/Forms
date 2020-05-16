<?php


namespace Casper\Fields;


class ResetButtonField extends BaseButtonField
{

    public function __construct()
    {
        $this->type = 'reset';
    }
}