<?php


namespace FormTests\fields;


use Casper\Fields\RadioField;
use FormTests\FormTestCase;

class RadioFieldTest extends FormTestCase
{
    public function testRadioFieldCorrectData(): void
    {
        $radio = 'cat';
        $radioField = (new RadioField())->choices(['bird','dog','cat']);
        $radioField->data($radio);
        self::assertTrue($radioField->getProperty('isValid'),'Radio field not valid');
        self::assertEquals($radio, $radioField->getProperty('cleanedData'));
        self::assertEmpty($radioField->getProperty('errorMessage'));
    }

    public function testRadioFieldInCorrectData(): void
    {
        $radio = 'wrong-radio';
        $radioField = (new RadioField())->choices(['bird','dog','cat']);
        $radioField->data($radio);
        self::assertFalse($radioField->getProperty('isValid'));
        self::assertNotEmpty($radioField->getProperty('errorMessage'));
    }
}