<?php


namespace FormTests\fields;


use Casper\Fields\SlugField;
use FormTests\FormTestCase;

class SlugFieldTest extends FormTestCase
{
    public function testSlugFieldCorrectData(): void
    {
        $slug = 'rtz-ptr';
        $slugField = new SlugField();
        $slugField->data($slug);
        self::assertTrue($slugField->getProperty('isValid'),'Slug field not valid');
        self::assertEquals($slug, $slugField->getProperty('cleanedData'));
        self::assertEmpty($slugField->getProperty('errorMessage'));
    }
}