<?php


namespace Casper\Validators;


use Casper\Exceptions\ValidationFailedException;
use Casper\Fields\BooleanField;
use Casper\Fields\CharField;
use Casper\Fields\CheckBoxField;
use Casper\Fields\ChoiceField;
use Casper\Fields\DateField;
use Casper\Fields\DateTimeField;
use Casper\Fields\EmailField;
use Casper\Fields\Fields;
use Casper\Fields\FileField;
use Casper\Fields\FloatField;
use Casper\Fields\ImageField;
use Casper\Fields\IntegerField;
use Casper\Fields\PasswordField;
use Casper\Fields\PhoneField;
use Casper\Fields\RadioField;
use Casper\Fields\RangeField;
use Casper\Fields\SlugField;
use Casper\Fields\TextField;
use Casper\Fields\TimeField;
use Casper\Fields\UrlField;
use Casper\Fields\UuidField;
use Casper\FormUtils;

class Validator implements ValidatorsInterface
{

    private const requiredErrorMessage = 'This field is required';
    private const allowNullErrorMessage = 'This field cannot be null';
    private const allowBlankErrorMessage = 'This field cannot be empty';
    private const regexErrorMessage = "The given value does not match the pattern %s ";

    private $data;

    /**
     * returns mixed
     * @param Fields $field
     * @return Fields
     * @throws ValidationFailedException
     */
    public function run(Fields $field): Fields
    {
        $this->data = $this->getData($field);

        if(!isset($this->data)){
            if($field->getProperty('required') === true){
                throw new ValidationFailedException(self::requiredErrorMessage);
            }
        }

        if(is_null($this->data)){
            if($field->getProperty('allowNull') === false){
                throw new ValidationFailedException(self::allowNullErrorMessage);
            }
        }

        if(empty($this->data)){
            if($field->getProperty('allowBlank') == false){
                throw new ValidationFailedException(self::allowBlankErrorMessage);
            }
        }

        if(!empty($regex = $field->getProperty('regex'))){
            if(!is_string($regex)){
                throw new ValidationFailedException(sprintf(self::regexErrorMessage,$regex));
            }

            $regex = '/'.trim($regex,'/').'/';
            if (is_string($regex) and !preg_match("{$regex}", $this->data)) {
                throw new ValidationFailedException(sprintf(self::regexErrorMessage,$regex));
            }
        }

        if(empty($this->data)){
            $field->setCleanedData($this->data);
            return null;
        }

        $fieldType = explode("\\", get_class($field));
        $fieldType = lcfirst($fieldType[count($fieldType)-1]);

        return (method_exists($this, $fieldType)) ? call_user_func_array([$this, $fieldType], [$field]) : $field ;
    }

    /**
     * @param Fields $field
     * @return mixed|null
     */
    private function getData(Fields $field)
    {
        $data = $field->getProperty('data');
        return $data;
    }

    /**
     * @param BooleanField $field
     * @return BooleanField
     * @throws ValidationFailedException
     */
    private function booleanField(BooleanField $field): BooleanField
    {
        if (!in_array(strtolower($this->data), [true, false, '1', '0', 'yes', 'no', 'ok'])) {
            throw new ValidationFailedException('Invalid boolean value');
        }

        $field->setCleanedData((bool) $this->data);
        return $field;
    }

    /**
     * @param CharField $field
     * @return CharField
     * @throws ValidationFailedException
     */
    private function charField(CharField $field): CharField
    {
        $this->checkMinLength($field->getProperty('minLength'), $this->data);
        $this->checkMaxLength($field->getProperty('maxVLength'), $this->data);

        $field->setCleanedData((string) $this->data);
        return $field;
    }

    /**
     * @param CheckBoxField $field
     * @return CheckBoxField
     */
    private function checkBoxField(CheckBoxField $field): CheckBoxField
    {
        return $field;
    }

    /**
     * @param ChoiceField $field
     * @return ChoiceField
     */
    private function choiceField(ChoiceField $field): ChoiceField
    {
        $choices = $field->getProperty('choices');
        if(isset($choices)){
            $delimiter = $field->getProperty('delimiter');
            $delimiter = !empty($delimiter) ?: '&';
            $choiceData = explode($delimiter, $this->data);
            if(FormUtils::isMultiDimensional($choices)){
                foreach ($choiceData as $key => $value){

                }
            }
            else
            {

            }
        }
        return $field;
    }

    /**
     * @param DateField $field
     * @return DateField
     * @throws ValidationFailedException
     */
    private function dateField(DateField $field): DateField
    {
        if(empty(strtotime($this->data))){
            throw new ValidationFailedException('Invalid date Value');
        }

        $field->setCleanedData(date('Y-m-d', strtotime($this->data)));
        return $field;
    }

    /**
     * @param DateTimeField $field
     * @return DateTimeField
     * @throws ValidationFailedException
     */
    private function dateTimeField(DateTimeField $field): DateTimeField
    {
        if(empty(strtotime($this->data))){
            throw new ValidationFailedException('Invalid date Value');
        }

        $field->setCleanedData(date('c', strtotime($this->data)));
        return $field;
    }

    /**
     * @param EmailField $field
     * @return EmailField
     * @throws ValidationFailedException
     */
    private function emailField(EmailField $field): EmailField
    {
        if (!filter_var($this->data, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationFailedException('Invalid email format');
        }

        $field->setCleanedData($this->data);
        return $field;
    }

    /**
     * @param FileField $field
     * @return FileField
     */
    private function fileField(FileField $field): FileField
    {
        return $field;
    }

    /**
     * @param FloatField $field
     * @return FloatField
     * @throws ValidationFailedException
     */
    private function floatField(FloatField $field): FloatField
    {
        if (!is_numeric($this->data)) {
            throw new ValidationFailedException('Invalid float value');
        }

        $this->checkMinValue($field->getProperty('minValue'), $this->data);
        $this->checkMaxValue($field->getProperty('maxValue'), $this->data);

        $field->setCleanedData((float) $this->data);
        return $field;
    }

    /**
     * @param ImageField $field
     * @return ImageField
     */
    private function imageField(ImageField $field): ImageField
    {
        return $field;
    }

    /**
     * @param IntegerField $field
     * @return IntegerField
     * @throws ValidationFailedException
     */
    private function integerField(IntegerField $field): IntegerField
    {
        if (!is_numeric($this->data) or is_numeric(strpos($this->data, '.'))) {
            throw new ValidationFailedException('Invalid integer value');
        }

        $this->checkMinValue($field->getProperty('minValue'), $this->data);
        $this->checkMaxValue($field->getProperty('maxValue'), $this->data);

        $field->setCleanedData((int) $this->data);
        return $field;
    }

    /**
     * @param $minValue
     * @param $data
     * @throws ValidationFailedException
     */
    private function checkMinValue($minValue, $data)
    {
        if(isset($minValue)){
            if($data < $minValue){
                throw new ValidationFailedException("Value cannot be less than {$minValue}");
            }
        }
    }

    /**
     * @param $maxValue
     * @param $data
     * @throws ValidationFailedException
     */
    private function checkMaxValue($maxValue, $data)
    {
        if(isset($maxValue)) {
            if ($data > $maxValue) {
                throw new ValidationFailedException("Value cannot be greater than {$maxValue}");
            }
        }
    }

    /**
     * @param $minLength
     * @param $data
     * @throws ValidationFailedException
     */
    private function checkMinLength($minLength, $data)
    {
        if(isset($minLength)){
            if(strlen($data) < $minLength){
                throw new ValidationFailedException("Value cannot be have length less than {$minLength}");
            }
        }
    }

    /**
     * @param $maxLength
     * @param $data
     * @throws ValidationFailedException
     */
    private function checkMaxLength($maxLength, $data)
    {
        if(isset($maxLength)){
            if(strlen($data) > $maxLength){
                throw new ValidationFailedException("Value cannot be have length more than {$maxLength}");
            }
        }
    }

    /**
     * @param PasswordField $field
     * @return PasswordField
     * @throws ValidationFailedException
     */
    private function passwordField(PasswordField $field): PasswordField
    {
        $this->checkMinLength($field->getProperty('minLength'), $this->data);
        $this->checkMaxLength($field->getProperty('maxLength'), $this->data);

        $upper = $field->getProperty('upper');
        $lower = $field->getProperty('lower');
        $number = $field->getProperty('number');
        $symbol = $field->getProperty('symbol');

        if(isset($upper)){
            if(!preg_match( '/[A-Z]/', $this->data )){
                throw new ValidationFailedException("Value must contain an upper case. ");
            }
        }

        if(isset($lower)){
            if(!preg_match( '/[a-z]/', $this->data )){
                throw new ValidationFailedException("Value must contain a lower case. ");
            }
        }

        if(isset($number)){
            if(!preg_match( '/\d/', $this->data )){
                throw new ValidationFailedException("Value must contain a numeric character. ");
            }
        }

        if(isset($symbol)){
            if(!preg_match( '/[^a-zA-Z\d]/', $this->data )){
                throw new ValidationFailedException("Value must contain a symbol. ");
            }
        }

        $field->setCleanedData((string) $this->data);
        return $field;
    }

    /**
     * @param PhoneField $field
     * @return PhoneField
     * @throws ValidationFailedException
     */
    private function phoneField(PhoneField $field): PhoneField
    {
        $internationalize = $field->getProperty('internationalize');

        if(isset($internationalize)){
            if(mb_substr($this->data, 0, 2) != '00' and mb_substr($this->data, 0, 1) != '+'){
                throw new ValidationFailedException("Value must be in international format. ");
            }
        }

        if (!preg_match("/^\s*(?:\+?(\d{1,3}))?([-. (]*(\d{3})[-. )]*)?((\d{3})[-. ]*(\d{2,4})(?:[-.x ]*(\d+))?)\s*$/", $this->data)) {
            throw new ValidationFailedException('Invalid phone format');
        }

        $field->setCleanedData($this->data);
        return $field;
    }

    /**
     * @param RadioField $field
     * @return RadioField
     */
    private function radioField(RadioField $field): RadioField
    {
        return $field;
    }

    /**
     * @param RangeField $field
     * @return RangeField
     * @throws ValidationFailedException
     */
    private function rangeField(RangeField $field): RangeField
    {
        if (!is_numeric($this->data)) {
            throw new ValidationFailedException('Invalid numeric value');
        }

        $this->checkMinValue($field->getProperty('minValue'), $this->data);
        $this->checkMaxValue($field->getProperty('maxValue'), $this->data);

        $field->setCleanedData((float) $this->data);
        return $field;
    }

    /**
     * @param SlugField $field
     * @return SlugField
     * @throws ValidationFailedException
     */
    private function slugField(SlugField $field): SlugField
    {
        $this->checkMinLength($field->getProperty('minLength'), $this->data);
        $this->checkMaxLength($field->getProperty('maxVLength'), $this->data);

        $field->setCleanedData(FormUtils::slugify($this->data));
        return $field;
    }

    /**
     * @param TextField $field
     * @return TextField
     * @throws ValidationFailedException
     */
    private function textField(TextField $field): TextField
    {
        $this->checkMinLength($field->getProperty('minLength'), $this->data);
        $this->checkMaxLength($field->getProperty('maxVLength'), $this->data);

        $field->setCleanedData((string) $this->data);
        return $field;
    }

    /**
     * @param TimeField $field
     * @return TimeField
     * @throws ValidationFailedException
     */
    private function timeField(TimeField $field): TimeField
    {
        if (!preg_match("/^([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/i", $this->data)) {
            throw new ValidationFailedException('Invalid time given.');
        }

        $field->setCleanedData($this->data);
        return $field;
    }

    /**
     * @param UuidField $field
     * @return UuidField
     * @throws ValidationFailedException
     */
    private function uuidField(UuidField $field): UuidField
    {
        if (!preg_match("/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i", $this->data)) {
            throw new ValidationFailedException('Invalid uuid format');
        }

        $field->setCleanedData($this->data);
        return $field;
    }

    /**
     * @param UrlField $field
     * @return UrlField
     * @throws ValidationFailedException
     */
    private function urlField(UrlField $field): UrlField
    {
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $this->data)) {
            throw new ValidationFailedException('Invalid url format');
        }

        $field->setCleanedData($this->data);
        return $field;
    }
}