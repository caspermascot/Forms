<?php


namespace Tests;


use Casper\Exceptions\FieldCreateFailedException;
use Casper\Exceptions\FormNotValidatedException;
use Casper\Exceptions\InvalidMethodException;
use Casper\Exceptions\InvalidUrlException;
use Exception;

class FormTest extends TestCase
{
    /**
     * @throws FieldCreateFailedException
     * @throws FormNotValidatedException
     * @throws InvalidMethodException
     * @throws InvalidUrlException
     * @throws Exception
     */
    public function testLoginForm()
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