<?php


namespace Tests;


use Casper\Forms;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;

class TestCase extends PHPUnit_TestCase
{

    protected function getTestForm(): Forms
    {
        return new TestForm();
    }
}

class TestForm extends Forms
{

    /**
     * add form fields, set method and url
     * return null
     */
    protected function build(): void
    {
        // TODO: Implement build() method.
    }
}