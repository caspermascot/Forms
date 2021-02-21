<?php


namespace FormTests\fields;


use Casper\Fields\PhoneField;
use FormTests\FormTestCase;

class PhoneFieldTest extends FormTestCase
{
    public function testPhoneFieldCorrectData(): void
    {
        $phone = '+35844565356744';
        $phoneField = (new PhoneField())->internationalFormat(true);
        $phoneField->data($phone);
        self::assertTrue($phoneField->getProperty('isValid'),'Phone field not valid');
        self::assertEquals($phone, $phoneField->getProperty('cleanedData'));
        self::assertEmpty($phoneField->getProperty('errorMessage'));
    }

    public function testPhoneFieldInCorrectData(): void
    {
        $phone = 'wrong-phone';
        $phoneField = (new PhoneField())->internationalFormat(true);
        $phoneField->data($phone);
        self::assertFalse($phoneField->getProperty('isValid'));
        self::assertNotEmpty($phoneField->getProperty('errorMessage'));
    }
}