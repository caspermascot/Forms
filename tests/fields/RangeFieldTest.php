<?php


namespace FormTests\fields;


use Casper\Fields\RangeField;
use FormTests\FormTestCase;

class RangeFieldTest extends FormTestCase
{
    public function testRangeFieldCorrectData(): void
    {
        $range = '55';
        $rangeField = (new RangeField())->minValue(5)->maxValue(80)->step(5);
        $rangeField->data($range);
        self::assertTrue($rangeField->getProperty('isValid'),'Range field not valid');
        self::assertEquals($range, $rangeField->getProperty('cleanedData'));
        self::assertEmpty($rangeField->getProperty('errorMessage'));
    }

    public function testRangeFieldInCorrectData(): void
    {
        $range = 'wrong-range';
        $rangeField = (new RangeField())->minValue(5)->maxValue(80)->step(5);
        $rangeField->data($range);
        self::assertFalse($rangeField->getProperty('isValid'));
        self::assertNotEmpty($rangeField->getProperty('errorMessage'));
    }
}