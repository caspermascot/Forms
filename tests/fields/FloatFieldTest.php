<?php


namespace FormTests\fields;


use Casper\Fields\FloatField;
use FormTests\FormTestCase;

class FloatFieldTest extends FormTestCase
{
    public function testFloatFieldCorrectData(): void
    {
        $float = '57.5';
        $floatField = new FloatField();
        $floatField->data($float);
        self::assertTrue($floatField->getProperty('isValid'),'Float field not valid');
        self::assertEquals($float, $floatField->getProperty('cleanedData'));
        self::assertEmpty($floatField->getProperty('errorMessage'));
    }

    public function testFloatFieldInCorrectData(): void
    {
        $float = 'wrong-float';
        $floatField = new FloatField();
        $floatField->data($float);
        self::assertFalse($floatField->getProperty('isValid'));
        self::assertNotEmpty($floatField->getProperty('errorMessage'));
    }
}