<?php


namespace Casper;


use Casper\Fields\BooleanField;
use Casper\Fields\CharField;
use Casper\Fields\CheckBoxField;
use Casper\Fields\ChoiceField;
use Casper\Fields\ColorField;
use Casper\Fields\DataListField;
use Casper\Fields\DateField;
use Casper\Fields\DateTimeField;
use Casper\Fields\EmailField;
use Casper\Fields\FileField;
use Casper\Fields\FloatField;
use Casper\Fields\HiddenField;
use Casper\Fields\ImageField;
use Casper\Fields\IntegerField;
use Casper\Fields\ListField;
use Casper\Fields\PasswordField;
use Casper\Fields\PhoneField;
use Casper\Fields\RadioField;
use Casper\Fields\RangeField;
use Casper\Fields\ResetButtonField;
use Casper\Fields\SlugField;
use Casper\Fields\SubmitButtonField;
use Casper\Fields\TextField;
use Casper\Fields\TimeField;
use Casper\Fields\UrlField;
use Casper\Fields\UuidField;

class FormFields implements FormFieldsInterface
{
    /**
     * @return BooleanField
     */
    public function booleanField(): BooleanField
    {
        return new BooleanField();
    }

    /**
     * @return CharField
     */
    public function charField(): CharField
    {
        return new CharField();
    }

    /**
     * @return CheckBoxField
     */
    public function checkBoxField(): CheckBoxField
    {
        return new CheckBoxField();
    }

    /**
     * @return ChoiceField
     */
    public function choiceField(): ChoiceField
    {
        return new ChoiceField();
    }

    /**
     * @return ColorField
     */
    public function colorField(): ColorField
    {
        return new ColorField();
    }

    /**
     * @return DataListField
     */
    public function dataListField(): DataListField
    {
        return new DataListField();
    }

    /**
     * @return DateField
     */
    public function dateField(): DateField
    {
        return new DateField();
    }

    /**
     * @return DateTimeField
     */
    public function dateTimeField(): DateTimeField
    {
        return new DateTimeField();
    }

    /**
     * @return EmailField
     */
    public function emailField(): EmailField
    {
        return new EmailField();
    }

    /**
     * @return FileField
     */
    public function fileField(): FileField
    {
        return new FileField();
    }

    /**
     * @return FloatField
     */
    public function floatField(): FloatField
    {
        return new FloatField();
    }

    /**
     * @return HiddenField
     */
    public function hiddenField(): HiddenField
    {
        return new HiddenField();
    }

    /**
     * @return ImageField
     */
    public function imageField(): ImageField
    {
        return new ImageField();
    }

    /**
     * @return IntegerField
     */
    public function integerField(): IntegerField
    {
        return new IntegerField();
    }

    /**
     * @return PasswordField
     */
    public function passwordField(): PasswordField
    {
        return new PasswordField();
    }

    /**
     * @return PhoneField
     */
    public function phoneField(): PhoneField
    {
        return new PhoneField();
    }

    /**
     * @return RadioField
     */
    public function radioField(): RadioField
    {
        return new RadioField();
    }

    /**
     * @return RangeField
     */
    public function rangeField(): RangeField
    {
        return new RangeField();
    }

    /**
     * @return SlugField
     */
    public function slugField(): SlugField
    {
        return new SlugField();
    }

    /**
     * @return TextField
     */
    public function textField(): TextField
    {
        return new TextField();
    }

    /**
     * @return TimeField
     */
    public function timeField(): TimeField
    {
        return new TimeField();
    }

    /**
     * @return UuidField
     */
    public function uuidField(): UuidField
    {
        return new UuidField();
    }

    /**
     * @return SubmitButtonField
     */
    public function submitButtonField(): SubmitButtonField
    {
        return new SubmitButtonField();
    }

    /**
     * @return ResetButtonField
     */
    public function resetButtonField(): ResetButtonField
    {
        return new ResetButtonField();
    }

    /**
     * @return UrlField
     */
    public function urlField(): UrlField
    {
        return new UrlField();
    }

    public function listField(): ListField
    {
        return new ListField();
    }
}