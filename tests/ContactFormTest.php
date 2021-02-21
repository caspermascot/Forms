<?php


namespace FormTests;


use Exception;
use FormTests\forms\ContactForm;

class ContactFormTest extends FormTestCase
{

    /**
     * @throws Exception
     */
    public function testValidContactForm(): void
    {
        $form = new ContactForm();
        $testData = [
            'firstName' => null,
            'email' => 'someone@example.com',
            'website' => 'www.example.com',
            'message' => 'contact form message with length greater than 15',
            'contactType' => 'enquiry'
        ];
        $form->setData($testData);
        self::assertTrue($form->isValid(),'contact form is not valid');
    }

    /**
     * @throws Exception
     */
    public function testContactFormWithNullEmail(): void
    {
        $form = new ContactForm();
        $testData = [
            'firstName' => null,
            'email' => null,
            'website' => 'www.example.com',
            'message' => 'contact form message with length greater than 15',
            'contactType' => 'enquiry'
        ];
        $form->setData($testData);
        self::assertNotTrue($form->isValid(),'contact form with null email is valid');
    }
}