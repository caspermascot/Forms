<?php


namespace FormTests\fields;


use Casper\Fields\BooleanField;
use FormTests\FormTestCase;

class BooleanFieldTest extends FormTestCase
{
    public function testBooleanFieldCorrectData(): void
    {
        $boolean = 'no';
        $booleanValue = false;
        $booleanField = new BooleanField();
        $booleanField->data($boolean);
        self::assertTrue($booleanField->getProperty('isValid'),'Boolean field not valid');
        self::assertEquals($booleanValue, $booleanField->getProperty('cleanedData'));
        self::assertEmpty($booleanField->getProperty('errorMessage'));
    }

    public function testBooleanFieldInCorrectData(): void
    {
        $boolean = 'wrong-boolean';
        $booleanField = new BooleanField();
        $booleanField->data($boolean);
        self::assertFalse($booleanField->getProperty('isValid'));
        self::assertNotEmpty($booleanField->getProperty('errorMessage'));
    }

    public function testNullBooleanField(): void
    {
        $booleanField = new BooleanField();
        $booleanField->required(false)->allowNull(true)->allowBlank(true)->data(null);
        self::assertTrue($booleanField->getProperty('isValid'));
        self::assertEmpty($booleanField->getProperty('errorMessage'));
    }
}