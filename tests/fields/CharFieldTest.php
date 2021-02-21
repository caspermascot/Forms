<?php


namespace FormTests\fields;


use Casper\Fields\CharField;
use FormTests\FormTestCase;

class CharFieldTest extends FormTestCase
{
    public function testCharFieldCorrectData(): void
    {
        $char = '57a13f5c-a629-4974-8d69-17c175923d1e';
        $charField = (new CharField())->minLength(15);
        $charField->data($char);
        self::assertTrue($charField->getProperty('isValid'),'Char field not valid');
        self::assertEquals($char, $charField->getProperty('cleanedData'));
        self::assertEmpty($charField->getProperty('errorMessage'));
    }

    public function testCharFieldInCorrectData(): void
    {
        $char = 'wrong-char';
        $charField = (new CharField())->minLength(15);
        $charField->data($char);
        self::assertFalse($charField->getProperty('isValid'));
        self::assertNotEmpty($charField->getProperty('errorMessage'));
    }
}