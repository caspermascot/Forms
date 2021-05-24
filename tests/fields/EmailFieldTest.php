<?php


namespace FormTests\fields;


use Casper\Fields\EmailField;
use FormTests\FormTestCase;

class EmailFieldTest extends FormTestCase
{
    public function testEmailFieldCorrectData(): void
    {
        $email = 'someone@example.com';
        $emailField = new EmailField();
        $emailField->data($email);
        self::assertTrue($emailField->getProperty('isValid'),'Email field not valid');
        self::assertEquals($email, $emailField->getProperty('cleanedData'));
        self::assertEmpty($emailField->getProperty('errorMessage'));
    }

    public function testEmailFieldWithWeirdCharacterData(): void
    {
        $email = 'ääp.järvi@veli.fi';
        $emailField = new EmailField();
        $emailField->data($email);
        self::assertTrue($emailField->getProperty('isValid'),'Email field not valid');
        self::assertEquals($email, $emailField->getProperty('cleanedData'));
        self::assertEmpty($emailField->getProperty('errorMessage'));
    }

    public function testEmailFieldInCorrectData(): void
    {
        $email = 'wrong-email';
        $emailField = new EmailField();
        $emailField->data($email);
        self::assertFalse($emailField->getProperty('isValid'));
        self::assertNotEmpty($emailField->getProperty('errorMessage'));
    }
}