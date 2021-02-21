<?php


namespace FormTests\fields;


use Casper\Fields\ChoiceField;
use FormTests\FormTestCase;

class ChoiceFieldTest extends FormTestCase
{
    public function testChoiceFieldCorrectData(): void
    {
        $choice = 'bird';
        $choiceField = (new ChoiceField())->choices(['bird','dog','cat']);
        $choiceField->data($choice);
        self::assertTrue($choiceField->getProperty('isValid'),'Choice field not valid');
        self::assertEquals($choice, $choiceField->getProperty('cleanedData'));
        self::assertEmpty($choiceField->getProperty('errorMessage'));
    }

    public function testChoiceFieldInCorrectData(): void
    {
        $choice = 'wrong-choice';
        $choiceField = (new ChoiceField())->choices(['bird','dog','cat']);
        $choiceField->data($choice);
        self::assertFalse($choiceField->getProperty('isValid'));
        self::assertNotEmpty($choiceField->getProperty('errorMessage'));
    }
}