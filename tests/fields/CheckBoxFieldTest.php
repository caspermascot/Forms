<?php


namespace FormTests\fields;


use Casper\Fields\CheckBoxField;
use FormTests\FormTestCase;

class CheckBoxFieldTest extends FormTestCase
{
    public function testCheckBoxFieldCorrectData(): void
    {
        $checkBox = 'dog';
        $checkBoxField = (new CheckBoxField())->choices(['bird','dog','cat']);
        $checkBoxField->data($checkBox);
        self::assertTrue($checkBoxField->getProperty('isValid'),'CheckBox field not valid');
        self::assertEquals($checkBox, $checkBoxField->getProperty('cleanedData'));
        self::assertEmpty($checkBoxField->getProperty('errorMessage'));
    }

    public function testCheckBoxFieldInCorrectData(): void
    {
        $checkBox = 'wrong-checkBox';
        $checkBoxField = (new CheckBoxField())->choices(['bird','dog','cat']);
        $checkBoxField->data($checkBox);
        self::assertFalse($checkBoxField->getProperty('isValid'));
        self::assertNotEmpty($checkBoxField->getProperty('errorMessage'));
    }
}