<?php


namespace Tests;


class FormTest extends TestCase
{
    public function testLoginForm()
    {
        $loginForm = new TestLoginForm();
        $testData = [

        ];
        $loginForm->setData($testData);
        if($loginForm->isValid()){
            self::assertEmpty($loginForm->getErrors());
        }else{
            self::assertEmpty($loginForm->getCleanedData());
        }
    }
}