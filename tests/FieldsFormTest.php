<?php


namespace Tests;


class FieldsFormTest extends FormTestCase
{

    public function testSubmitButtonField()
    {
        $form = $this->getTestForm();
        $submitButton = $form->submitButtonField();
        self::assertIsString($submitButton->asHtml());
        self::assertStringContainsString("type='submit'", $submitButton->asHtml());
    }

    public function testCreateChoiceField()
    {
        $form = $this->getTestForm();
        $choice = $form->choiceField()->multiple(true)->choices(['a' => 1,'b' => 2,'c' => 3,'d' => 4]);
        self::assertArrayHasKey('a', $choice->getProperty('choices'));
    }
}