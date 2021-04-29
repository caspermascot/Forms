<?php


namespace FormTests\fields;

use Casper\Fields\ListField;
use FormTests\FormTestCase;

class ListFieldTest extends FormTestCase
{
    public function testIntegerListFieldCorrectData(): void
    {
        $list = '57,44,54';
        $listField = (new ListField())->type('Integer')->minValue(40);
        $listField->data($list);
        self::assertTrue($listField->getProperty('isValid'),'List field not valid');
        self::assertEquals($list, $listField->getProperty('cleanedData'));
        self::assertEmpty($listField->getProperty('errorMessage'));
    }
}