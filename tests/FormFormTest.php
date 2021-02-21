<?php


namespace FormTests;


use Casper\Exceptions\FormNotValidatedException;
use Exception;

class FormFormTest extends FormTestCase
{
    /**
     * @throws FormNotValidatedException
     * @throws Exception
     */
    public function testLoginForm(): void
    {
        $loginForm = new TestLoginForm();
        $testData = [

        ];
        $loginForm->setData($testData);
        if($loginForm->isValid()){
            self::assertEmpty($loginForm->getErrors());
        }else{
            print_r($loginForm->getErrors());
            self::assertEmpty($loginForm->getCleanedData());
        }
    }
}