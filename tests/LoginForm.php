<?php


namespace Tests;


use Casper\Exceptions\InvalidButtonTypeException;
use Casper\Exceptions\ValidationFailedException;
use Casper\Fields\BaseField;
use Casper\Fields\ButtonField;
use Casper\Fields\CharField;
use Casper\Fields\CheckBoxField;
use Casper\Fields\ChoiceField;
use Casper\Fields\ColorField;
use Casper\Fields\DataListField;
use Casper\Fields\DateField;
use Casper\Fields\EmailField;
use Casper\Fields\Fields;
use Casper\Fields\FloatField;
use Casper\Fields\HiddenField;
use Casper\Fields\ImageField;
use Casper\Fields\IntegerField;
use Casper\Fields\PasswordField;
use Casper\Fields\PhoneField;
use Casper\Fields\RadioField;
use Casper\Fields\RangeField;
use Casper\Fields\ResetButtonField;
use Casper\Fields\SubmitButtonField;
use Casper\Fields\TextField;
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
     * @var ResetButtonField
     */
    public ResetButtonField $reset;
    /**
     * @var BaseField|ButtonField
     */
    public ButtonField $button;
    /**
     * @var HiddenField
     */
    public HiddenField $hidden;
    /**
     * @var ColorField
     */
    public ColorField $color;
    /**
     * @var TextField
     */
    public TextField $textarea;
    /**
     * @var ImageField
     */
    public ImageField $photo;
    /**
     * @var DataListField
     */
    public DataListField $dataList;

    /**
     * return null
     * @throws InvalidButtonTypeException
     */
    protected function build(): void
    {
        $this->age = $this->integerField()->label('ish')->maxValue(450)->default(45)->step(4)->required(false);
        $this->name = $this->charField()->regex("^[a-zA-Z ]*$");
        $this->email = $this->emailField()->required(true)->customErrorMessages('invalid email address');
        $this->url = $this->urlField()->required(true);
        $this->password = $this->passwordField()->required(true)
                            ->mustContainLowerCase(true)
                            ->mustContainNumber(true)
                            ->mustContainSymbol(true)
                            ->mustContainUpperCase(true)
                            ->minLength(8)
                            ->maxLength(10)
                            ->helpText('symbol, number, lower case, upper case');
        $this->phone = $this->phoneField()->internationalFormat(true)->required(false);
        $this->date = $this->dateField()->default('2020-05-22')->minValue('2020-05-20')->required(false);
        $this->select = $this->choiceField()->multiple(true)->choices(['male','female','each','unknown'])->default(['male','female'])->required(false);
        $this->submit = $this->submitButtonField()->type('submit');
        $this->float = $this->floatField()->step(0.3);
        $this->range = $this->rangeField();
        $this->radio = $this->radioField()->choices(['man','woman','each'])->autoFocus(true)->default('man')->required(false);
        $this->checkBox = $this->checkBoxField()->choices(['man','woman','each'])
            ->autoFocus(true)
            ->default('man')
            ->required(false)
            ->label('check-boxing')
            ->multiple(true);

        $this->reset = $this->resetButtonField();
        $this->button = $this->buttonField()->label('stylish-button');
        $this->hidden = $this->hiddenField();
        $this->color = $this->colorField();
        $this->textarea = $this->textField()->cols(40)->rows(5);
        $this->photo = $this->imageField()->width(200)->height(10)->alt('image');
        $this->dataList = $this->dataListField()->choices(['audi','benz','bmw','pagani']);
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
            throw new ValidationFailedException('invalid name');

        return $data['name'];
    }
}