<?php


namespace Tests;


use Casper\Exceptions\InvalidButtonTypeException;
use Casper\Exceptions\ValidationFailedException;
use Casper\Fields\CharField;
use Casper\Fields\CheckBoxField;
use Casper\Fields\ChoiceField;
use Casper\Fields\DateField;
use Casper\Fields\EmailField;
use Casper\Fields\Fields;
use Casper\Fields\FloatField;
use Casper\Fields\IntegerField;
use Casper\Fields\PasswordField;
use Casper\Fields\PhoneField;
use Casper\Fields\RadioField;
use Casper\Fields\RangeField;
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
    public SubmitButtonField $submit;
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
     * @var FloatField|IntegerField
     */
    public FloatField $float;
    /**
     * @var RangeField
     */
    public RangeField $range;
    /**
     * @var RadioField
     */
    public RadioField $radio;
    /**
     * @var CheckBoxField|Fields
     */
    public CheckBoxField $checkBox;

    /**
     * return null
     * @throws InvalidButtonTypeException
     */
    protected function build(): void
    {
        $this->age = $this->integerField()->label('ish')->maxValue(450)->default(45)->step(4)->required(false);
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
        $this->phone = $this->fields->phoneField()->internationalFormat(true)->required(false);
        $this->date = $this->fields->dateField()->default('2020-05-22')->minValue('2020-05-20')->required(false);
        $this->select = $this->fields->choiceField()->multiple(true)->choices(['male','female','each','unknown'])->default(['male','female'])->required(false);
        $this->submit = $this->fields->submitButtonField()->type('submit');
        $this->float = $this->floatField()->step(0.3);
        $this->range = $this->rangeField();
        $this->radio = $this->radioField()->choices(['man','woman','each'])->autoFocus(true)->default('man')->required(false);
        $this->checkBox = $this->checkBoxField()->choices(['man','woman','each'])
            ->autoFocus(true)
            ->default('man')
            ->required(false)
            ->label('check-boxing')
            ->multiple(true);
        $this->setUrl('http://localhost/forms/');
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