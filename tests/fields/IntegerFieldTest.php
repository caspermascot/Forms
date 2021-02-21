<?php


namespace FormTests\fields;


use Casper\Fields\IntegerField;
use FormTests\FormTestCase;

class IntegerFieldTest extends FormTestCase
{
    public function testIntegerFieldCorrectData(): void
    {
        $integer = '57';
        $integerField = (new IntegerField())->minValue(1);
        $integerField->data($integer);
        self::assertTrue($integerField->getProperty('isValid'),'Integer field not valid');
        self::assertEquals($integer, $integerField->getProperty('cleanedData'));
        self::assertEmpty($integerField->getProperty('errorMessage'));
    }

    public function testIntegerFieldInCorrectData(): void
    {
        $integer = 'wrong-integer';
        $integerField = (new IntegerField())->minValue(1);
        $integerField->data($integer);
        self::assertFalse($integerField->getProperty('isValid'));
        self::assertNotEmpty($integerField->getProperty('errorMessage'));
    }
}