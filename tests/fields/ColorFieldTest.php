<?php


namespace FormTests\fields;


use Casper\Fields\ColorField;
use FormTests\FormTestCase;

class ColorFieldTest extends FormTestCase
{
    public function testColorFieldCorrectData(): void
    {
        $color = '#fff';
        $colorField = new ColorField();
        $colorField->data($color);
        self::assertTrue($colorField->getProperty('isValid'),'Color field not valid');
        self::assertEquals($color, $colorField->getProperty('cleanedData'));
        self::assertEmpty($colorField->getProperty('errorMessage'));
    }

    public function testColorFieldInCorrectData(): void
    {
        $color = 'wrong-color';
        $colorField = new ColorField();
        $colorField->data($color);
        self::assertFalse($colorField->getProperty('isValid'));
        self::assertNotEmpty($colorField->getProperty('errorMessage'));
    }
}