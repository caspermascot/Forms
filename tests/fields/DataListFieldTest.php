<?php


namespace FormTests\fields;


use Casper\Fields\DataListField;
use FormTests\FormTestCase;

class DataListFieldTest extends FormTestCase
{
    public function testDataListFieldCorrectData(): void
    {
        $dataList = 'dog';
        $dataListField = (new DataListField())
            ->choices(['bird','dog','cat'])
            ->allowNewContent(false);
        $dataListField->data($dataList);
        self::assertTrue($dataListField->getProperty('isValid'),'DataList field not valid');
        self::assertEquals($dataList, $dataListField->getProperty('cleanedData'));
        self::assertEmpty($dataListField->getProperty('errorMessage'));
    }

    public function testDataListFieldInCorrectData(): void
    {
        $dataList = 'wrong-dataList';
        $dataListField = (new DataListField())
            ->choices(['bird','dog','cat'])
            ->allowNewContent(false);
        $dataListField->data($dataList);
        self::assertFalse($dataListField->getProperty('isValid'));
        self::assertNotEmpty($dataListField->getProperty('errorMessage'));
    }
}