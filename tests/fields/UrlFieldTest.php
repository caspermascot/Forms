<?php


namespace FormTests\fields;


use Casper\Fields\UrlField;
use FormTests\FormTestCase;

class UrlFieldTest extends FormTestCase
{
    public function testUrlFieldCorrectData(): void
    {
        $url = 'http:://www.example.com';
        $urlField = new UrlField();
        $urlField->data($url);
        self::assertTrue($urlField->getProperty('isValid'),'Url field not valid');
        self::assertEquals($url, $urlField->getProperty('cleanedData'));
        self::assertEmpty($urlField->getProperty('errorMessage'));
    }

    public function testUrlFieldInCorrectData(): void
    {
        $url = 'wrong-url';
        $urlField = new UrlField();
        $urlField->data($url);
        self::assertFalse($urlField->getProperty('isValid'));
        self::assertNotEmpty($urlField->getProperty('errorMessage'));
    }
}
