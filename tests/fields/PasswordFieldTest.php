<?php


namespace FormTests\fields;


use Casper\Fields\PasswordField;
use FormTests\FormTestCase;

class PasswordFieldTest extends FormTestCase
{
    public function testPasswordFieldCorrectData(): void
    {
        $password = 'Password1-';
        $passwordField = (new PasswordField())
            ->minLength(8)
            ->mustContainLowerCase(true)
            ->mustContainNumber(true)
            ->mustContainSymbol(true)
            ->mustContainUpperCase(true);
        $passwordField->data($password);
        self::assertTrue($passwordField->getProperty('isValid'),'Password field not valid');
        self::assertEquals($password, $passwordField->getProperty('cleanedData'));
        self::assertEmpty($passwordField->getProperty('errorMessage'));
    }

    public function testPasswordFieldInCorrectData(): void
    {
        $password = 'wrong-password';
        $passwordField = (new PasswordField())
            ->minLength(8)
            ->mustContainLowerCase(true)
            ->mustContainNumber(true)
            ->mustContainSymbol(true)
            ->mustContainUpperCase(true);
        $passwordField->data($password);
        self::assertFalse($passwordField->getProperty('isValid'));
        self::assertNotEmpty($passwordField->getProperty('errorMessage'));
    }
}