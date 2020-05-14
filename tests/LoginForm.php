<?php


namespace Tests;


use Casper\Exceptions\InvalidButtonTypeException;
use Casper\Exceptions\ValidationFailedException;
use Casper\Fields\CharField;
use Casper\Fields\EmailField;
use Casper\Fields\Fields;
use Casper\Fields\IntegerField;
use Casper\Fields\PasswordField;
use Casper\Fields\PhoneField;
use Casper\Fields\SubmitButtonField;
use Casper\Forms;

class LoginForm extends Forms
{
    /**
     * @var IntegerField
     */
    public IntegerField $age;
    /**
     * @var CharField
     */
    public CharField $name;
    /**
     * @var SubmitButtonField
     */
    private SubmitButtonField $submit;
    /**
     * @var EmailField|Fields
     */
    public EmailField $email;
    /**
     * @var Fields|PhoneField
     */
    public $phone;
    /**
     * @var Fields|PasswordField
     */
    public $password;

    /**
     * return null
     * @throws InvalidButtonTypeException
     */
    protected function build(): void
    {
        $this->age = $this->fields->integerField()->required(true)->label('ish')->maxValue(450);
        $this->name = $this->fields->charField()->regex("^[a-zA-Z ]*$");
        $this->email = $this->fields->emailField()->required(true);
        $this->url = $this->fields->urlField()->required(true);
        $this->password = $this->fields->passwordField()->required(true)
                            ->mustContainLowerCase(true)
                            ->mustContainNumber(true)
                            ->mustContainSymbol(true)
                            ->mustContainUpperCase(true)
                            ->minLength(8)
                            ->maxLength(10);
        $this->phone = $this->fields->phoneField()->internationalFormat(true)->required(true);
        $this->date = $this->fields->dateField()->required(true);
        $this->submit = $this->fields->submitButtonField()->type('submit');
        $this->setUrl('www.google.com');
    }

    /**
     * @return mixed
     * @throws ValidationFailedException
     */
    public function validate_name()
    {
        $data = $this->getData();
        if(empty($data['name']))
            throw new ValidationFailedException('i hate you');

        return $data['name'];
    }
}