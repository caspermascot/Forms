<?php


namespace FormTests\fields;


use Casper\Fields\DateTimeField;
use FormTests\FormTestCase;

class DateTimeFieldTest extends FormTestCase
{
    public function testDateTimeFieldCorrectData(): void
    {
        $dateTime = '2019-07-06 13:04:23';
        $dateTimeField = new DateTimeField();
        $dateTimeField->data($dateTime);
        self::assertTrue($dateTimeField->getProperty('isValid'),'DateTime field not valid');
        self::assertEquals($dateTime, $dateTimeField->getProperty('cleanedData'));
        self::assertEmpty($dateTimeField->getProperty('errorMessage'));
    }

    public function testDateTimeFieldInCorrectData(): void
    {
        $dateTime = 'wrong-dateTime';
        $dateTimeField = new DateTimeField();
        $dateTimeField->data($dateTime);
        self::assertFalse($dateTimeField->getProperty('isValid'));
        self::assertNotEmpty($dateTimeField->getProperty('errorMessage'));
    }
}