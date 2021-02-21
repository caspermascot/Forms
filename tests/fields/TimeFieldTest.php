<?php


namespace FormTests\fields;


use Casper\Fields\TimeField;
use FormTests\FormTestCase;

class TimeFieldTest extends FormTestCase
{
    public function testTimeFieldCorrectData(): void
    {
        $time = '13:00:00';
        $timeField = new TimeField();
        $timeField->data($time);
        self::assertTrue($timeField->getProperty('isValid'),'Time field not valid');
        self::assertEquals($time, $timeField->getProperty('cleanedData'));
        self::assertEmpty($timeField->getProperty('errorMessage'));
    }

    public function testTimeFieldInCorrectData(): void
    {
        $time = 'wrong-url';
        $timeField = new TimeField();
        $timeField->data($time);
        self::assertFalse($timeField->getProperty('isValid'));
        self::assertNotEmpty($timeField->getProperty('errorMessage'));
    }
}