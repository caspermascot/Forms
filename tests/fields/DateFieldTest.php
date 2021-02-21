<?php


namespace FormTests\fields;


use Casper\Fields\DateField;
use FormTests\FormTestCase;

class DateFieldTest extends FormTestCase
{
    public function testDateFieldCorrectData(): void
    {
        $date = '2019-12-31';
        $dateField = new DateField();
        $dateField->data($date);
        self::assertTrue($dateField->getProperty('isValid'),'Date field not valid');
        self::assertEquals($date, $dateField->getProperty('cleanedData'));
        self::assertEmpty($dateField->getProperty('errorMessage'));
    }

    public function testDateFieldInCorrectData(): void
    {
        $date = 'wrong-date';
        $dateField = new DateField();
        $dateField->data($date);
        self::assertFalse($dateField->getProperty('isValid'));
        self::assertNotEmpty($dateField->getProperty('errorMessage'));
    }
}