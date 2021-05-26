<?php


namespace FormTests\fields;


use Casper\Fields\TextField;
use FormTests\FormTestCase;

class TextFieldTest extends FormTestCase
{
    public function testTextFieldCorrectData(): void
    {
        $slug = 'rtz-ptr';
        $slugField = new TextField();
        $slugField->data($slug);
        self::assertTrue($slugField->getProperty('isValid'),'text field not valid');
        self::assertEquals($slug, $slugField->getProperty('cleanedData'));
        self::assertEmpty($slugField->getProperty('errorMessage'));
    }


    public function testTextFieldArrayData(): void
    {
        $slug = ['foo' => 'rtz-ptr'];
        $slugField = new TextField();
        $slugField->data($slug);
        self::assertTrue($slugField->getProperty('isValid'),'text field not valid');
        self::assertEmpty($slugField->getProperty('errorMessage'));
    }

}