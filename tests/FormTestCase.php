<?php


namespace Tests;


use Casper\Forms;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;

class FormTestCase extends PHPUnit_TestCase
{

    protected function getTestForm(): Forms
    {
        return new EmptyTestForm();
    }
}