<?php


namespace Tests;


use Casper\Exceptions\InvalidButtonTypeException;
use Casper\Exceptions\ValidationFailedException;
use Casper\Fields\CharField;
use Casper\Fields\ChoiceField;
use Casper\Fields\DateField;
use Casper\Fields\EmailField;
use Casper\Fields\Fields;
use Casper\Fields\IntegerField;
use Casper\Fields\PasswordField;
use Casper\Fields\PhoneField;
use Casper\Fields\SubmitButtonField;
use Casper\Fields\UrlField;
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
    public PhoneField $phone;
    /**
     * @var Fields|PasswordField
     */
    public PasswordField $password;
    /**
     * @var DateField|Fields
     */
    public DateField $date;
    /**
     * @var Fields|UrlField
     */
    public UrlField $url;
    /**
     * @var ChoiceField|Fields
     */
    public ChoiceField $select;

    /**
     * return null
     * @throws InvalidButtonTypeException
     */
    protected function build(): void
    {
        $this->age = $this->integerField()->label('ish')->maxValue(450)->default(45)->step(4);
        $this->name = $this->fields->charField()->regex("^[a-zA-Z ]*$");
        $this->email = $this->fields->emailField()->required(true)->customErrorMessages('csm');
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
        $this->select = $this->fields->choiceField()->multiple(true)->choices(['male','female'])->default(['male','female']);
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