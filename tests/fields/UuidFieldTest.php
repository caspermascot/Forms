<?php


namespace FormTests\fields;


use Casper\Fields\UuidField;
use FormTests\FormTestCase;

class UuidFieldTest extends FormTestCase
{
    public function testUuidFieldCorrectData(): void
    {
        $uuid = '57a13f5c-a629-4974-8d69-17c175923d1e';
        $uuidField = new UuidField();
        $uuidField->data($uuid);
        self::assertTrue($uuidField->getProperty('isValid'),'Uuid field not valid');
        self::assertEquals($uuid, $uuidField->getProperty('cleanedData'));
        self::assertEmpty($uuidField->getProperty('errorMessage'));
    }

    public function testUuidFieldInCorrectData(): void
    {
        $uuid = 'wrong-uuid';
        $uuidField = new UuidField();
        $uuidField->data($uuid);
        self::assertFalse($uuidField->getProperty('isValid'));
        self::assertNotEmpty($uuidField->getProperty('errorMessage'));
    }
}