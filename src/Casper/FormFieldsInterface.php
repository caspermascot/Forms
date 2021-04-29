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

interface FormFieldsInterface
{
    /**
     * @return BooleanField
     */
    public function booleanField(): BooleanField;

    /**
     * @return CharField
     */
    public function charField(): CharField;

    /**
     * @return CheckBoxField
     */
    public function checkBoxField(): CheckBoxField;

    /**
     * @return ChoiceField
     */
    public function choiceField(): ChoiceField;

    /**
     * @return ColorField
     */
    public function colorField(): ColorField;

    /**
     * @return DataListField
     */
    public function dataListField(): DataListField;

    /**
     * @return DateField
     */
    public function dateField(): DateField;

    /**
     * @return DateTimeField
     */
    public function dateTimeField(): DateTimeField;

    /**
     * @return EmailField
     */
    public function emailField(): EmailField;

    /**
     * @return FileField
     */
    public function fileField(): FileField;

    /**
     * @return FloatField
     */
    public function floatField(): FloatField;

    /**
     * @return HiddenField
     */
    public function hiddenField(): HiddenField;

    /**
     * @return ImageField
     */
    public function imageField(): ImageField;

    /**
     * @return IntegerField
     */
    public function integerField(): IntegerField;

    /**
     * @return PasswordField
     */
    public function passwordField(): PasswordField;

    /**
     * @return PhoneField
     */
    public function phoneField(): PhoneField;

    /**
     * @return RadioField
     */
    public function radioField(): RadioField;

    /**
     * @return RangeField
     */
    public function rangeField(): RangeField;

    /**
     * @return SlugField
     */
    public function slugField(): SlugField;

    /**
     * @return TextField
     */
    public function textField(): TextField;

    /**
     * @return TimeField
     */
    public function timeField(): TimeField;

    /**
     * @return UuidField
     */
    public function uuidField(): UuidField;

    /**
     * @return SubmitButtonField
     */
    public function submitButtonField(): SubmitButtonField;

    /**
     * @return ResetButtonField
     */
    public function resetButtonField(): ResetButtonField;

    /**
     * @return UrlField
     */
    public function urlField(): UrlField;

    /**
     * @return ListField
     */
    public function listField(): ListField;
}