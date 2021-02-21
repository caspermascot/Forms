<?php


namespace Casper\Validators;


use Casper\Exceptions\ValidationFailedException;
use Casper\Fields\BooleanField;
use Casper\Fields\CharField;
use Casper\Fields\CheckBoxField;
use Casper\Fields\ChoiceField;
use Casper\Fields\ColorField;
use Casper\Fields\DataListField;
use Casper\Fields\DateField;
use Casper\Fields\DateTimeField;
use Casper\Fields\EmailField;
use Casper\Fields\Fields;
use Casper\Fields\FileField;
use Casper\Fields\FloatField;
use Casper\Fields\HiddenField;
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
use Exception;

class Validator implements ValidatorsInterface
{
    private const requiredErrorMessage = 'This field is required';
    private const allowNullErrorMessage = 'This field cannot be null';
    private const allowBlankErrorMessage = 'This field cannot be empty';
    private const regexErrorMessage = "The given value does not match the pattern %s ";

    /**
     * @var
     */
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

        if(!isset($this->data) && $field->getProperty('required') === true) {
            throw new ValidationFailedException(self::requiredErrorMessage);
        }
    
        if(isset($this->data)){
            if(is_null($this->data) && $field->getProperty('allowNull') === false) {
                throw new ValidationFailedException(self::allowNullErrorMessage);
            }
            
            if(empty($this->data) && (($this instanceof IntegerField || !$this instanceof BooleanField) === false) && $field->getProperty('allowBlank') === false) {
                throw new ValidationFailedException(self::allowBlankErrorMessage);
            }
        }

        if(!empty($regex = $field->getProperty('regex'))){
            if(!is_string($regex)){
                throw new ValidationFailedException(sprintf(self::regexErrorMessage,$regex));
            }

            $regex = '/'.trim($regex,'/').'/';
            if (is_string($regex) && !preg_match(($regex), $this->data)) {
                throw new ValidationFailedException(sprintf(self::regexErrorMessage,$regex));
            }
        }

        if(empty($this->data)){
            $field->setCleanedData($this->data);
            return $field;
        }

        $fieldType = FormUtils::getFieldType($field);

        return (method_exists($this, $fieldType)) ? call_user_func_array([$this, $fieldType], [$field]) : $field ;
    }

    /**
     * @param Fields $field
     * @return mixed|null
     */
    private function getData(Fields $field)
    {
        $data = $field->getProperty('data');
        // perhaps look in $_FILES
        if(empty($data) && ($field instanceof FileField || $field instanceof ImageField)) {
            $name = $field->getProperty('name');
            if(!empty($_FILES[$name]['name'][0])){
                $data = $_FILES[$name];
                $field->setData($data);
            }
        }
        return $data;
    }

    /**
     * @param BooleanField $field
     * @return BooleanField
     * @throws ValidationFailedException
     */
    private function booleanField(BooleanField $field): BooleanField
    {
        if(!is_bool($this->data) && !in_array(strtolower($this->data), ['true', 'false', '1', '0', 'yes', 'no', 'ok'])) {
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
        $this->checkMaxLength($field->getProperty('maxLength'), $this->data);

        $field->setCleanedData((string) $this->data);
        return $field;
    }

    /**
     * @param CheckBoxField $field
     * @return CheckBoxField
     * @throws ValidationFailedException
     */
    private function checkBoxField(CheckBoxField $field): CheckBoxField
    {
        $field->setCleanedData($this->validateChoiceOptions($field));
        return $field;
    }

    /**
     * @param ChoiceField $field
     * @return ChoiceField
     * @throws ValidationFailedException
     */
    private function choiceField(ChoiceField $field): ChoiceField
    {
        $field->setCleanedData($this->validateChoiceOptions($field));
        return $field;
    }

    /**
     * @param Fields $field
     * @return string|null
     * @throws ValidationFailedException
     */
    private function validateChoiceOptions(Fields $field): ?string
    {
        $choices = $field->getProperty('choices');
        $choiceOptions = is_array($this->data) ? $this->data : explode(',',$this->data);

        if(isset($choices)){
            foreach ($choiceOptions as $key => $value){
                if(!in_array($value, $choices, true)){
                    throw new ValidationFailedException(sprintf("{$value} is not a valid option for this field, supported options are [' %s '] ", implode(',',$choices)));
                }
            }
        }

        return implode(',',$choiceOptions);
    }

    /**
     * @param ColorField $field
     * @return ColorField
     * @throws ValidationFailedException
     */
    private function colorField(ColorField $field): ColorField
    {
        if(!preg_match("/#([a-f0-9]{3}){1,2}\b/i", $this->data)){
            throw new ValidationFailedException('Invalid hex color');
        }
        $field->setCleanedData($this->data);
        return $field;
    }

    /**
     * @param DataListField $field
     * @return DataListField
     * @throws ValidationFailedException
     */
    private function dataListField(DataListField $field): DataListField
    {
        if($field->getProperty('allowNewContent') === false){
            $field->setCleanedData($this->validateChoiceOptions($field));
        }else{
            // no point in validating this
            $choiceOptions = is_array($this->data) ? $this->data : explode(',',$this->data);
            $data = implode(',',$choiceOptions);
            $field->setCleanedData($data);
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

        $this->validateDate($field);

        $field->setCleanedData(date('Y-m-d', strtotime($this->data)));
        return $field;
    }

    /**
     * @param DateField $field
     * @throws ValidationFailedException
     */
    private function validateDate(DateField $field): void
    {
        $minValue = $field->getProperty('minValue');
        if(isset($minValue) && strtotime($minValue) < strtotime($this->data)) {
            throw new ValidationFailedException("Value cannot be less than {$minValue}");
        }

        $maxValue = $field->getProperty('maxValue');
        if(isset($maxValue) && strtotime($this->data) > strtotime($maxValue)) {
            throw new ValidationFailedException("Value cannot be greater than {$maxValue}");
        }
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

        $this->validateDate($field);

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
     * @throws ValidationFailedException
     */
    private function fileField(FileField $field): FileField
    {
        $this->validateFileProperties($field);
        $field->setCleanedData($this->data);
        return $field;
    }

    /**
     * @param FileField $field
     * @throws ValidationFailedException
     */
    private function validateFileProperties(FileField $field): void
    {
        $multiple = $field->getProperty('multiple');

        if($multiple === true){
            $count = count($this->data['size']);
            $minSize = $field->getProperty('minSize');
            for($index = 0; $index < $count; ++$index) {
                if (isset($minSize) && $this->data["size"][$index] < $minSize) {
                    throw new ValidationFailedException(sprintf("Size cannot be less than %s mb", $minSize/1000000));
                }

                $maxSize = $field->getProperty('maxSize');
                if (isset($maxSize) && $this->data["size"][$index] > $maxSize) {
                    throw new ValidationFailedException(sprintf("Size cannot be greater than %s mb", $maxSize/1000000));
                }

                $type = $field->getProperty('type');
                if (isset($type)) {
                    $dir = "uploads/";
                    $target_file = $dir . basename($this->data["name"][$index]);
                    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                    $type = array_values($type);
                    if (!in_array(strtolower($fileType), $type, true)) {
                        throw new ValidationFailedException("Unsupported type given. Must be one of ( " . implode($type) . " )");
                    }
                }
            }
        }else{
            $minSize = $field->getProperty('minSize');
            if(isset($minSize) && $this->data["size"] < $minSize) {
                throw new ValidationFailedException("Size cannot be less than {$minSize}");
            }

            $maxSize = $field->getProperty('maxSize');
            if(isset($maxSize) && $this->data["size"] > $maxSize) {
                throw new ValidationFailedException("Size cannot be greater than {$maxSize}");
            }

            $type = $field->getProperty('type');
            if(isset($type)){
                $dir = "uploads/";
                $target_file = $dir . basename($this->data["name"]);
                $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                $type = array_values($type);
                if(!in_array(strtolower($fileType), $type, true)){
                    throw new ValidationFailedException("Unsupported type given. Must be one of ( ".implode($type)." )");
                }
            }
        }



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
     * @param HiddenField $field
     * @return HiddenField
     * @throws ValidationFailedException
     */
    private function hiddenField(HiddenField $field): HiddenField
    {
        $this->checkMinLength($field->getProperty('minLength'), $this->data);
        $this->checkMaxLength($field->getProperty('maxLength'), $this->data);

        $field->setCleanedData((string) $this->data);
        return $field;
    }

    /**
     * @param ImageField $field
     * @return ImageField
     * @throws ValidationFailedException
     */
    private function imageField(ImageField $field): ImageField
    {
        $multiple = $field->getProperty('multiple');
        if($multiple === true){
            $count = count($this->data['tmp_name']);

            for($index = 0; $index < $count; ++$index){
                try{
                    if(empty($this->data["tmp_name"][$index])){
                        $check = false;
                    }else{
                        $check = getimagesize($this->data["tmp_name"][$index]);
                    }
                }catch (Exception $exception){
                    $check = false;
                }

                if($check === false) {
                    throw new ValidationFailedException('File is not an image');
                }
            }
        }else{
            try{
                if(empty($this->data["tmp_name"])){
                    $check = false;
                }else{
                    $check = getimagesize($this->data["tmp_name"]);
                }
            }catch (Exception $exception){
                $check = false;
            }

            if($check === false) {
                throw new ValidationFailedException('File is not an image');
            }
        }

        $this->validateFileProperties($field);
        $field->setCleanedData($this->data);
        return $field;
    }

    /**
     * @param IntegerField $field
     * @return IntegerField
     * @throws ValidationFailedException
     */
    private function integerField(IntegerField $field): IntegerField
    {
        if (!is_numeric($this->data) || is_numeric(strpos($this->data, '.'))) {
            throw new ValidationFailedException('Invalid integer value');
        }

        $this->checkMinValue($field->getProperty('minValue'), $this->data);
        $this->checkMaxValue($field->getProperty('maxValue'), $this->data);

        $this->data /= 1;
        $field->setCleanedData((int) $this->data);
        return $field;
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

        if(isset($upper) && !preg_match('/[A-Z]/', $this->data)) {
            throw new ValidationFailedException("Value must contain an upper case. ");
        }

        if(isset($lower) && !preg_match('/[a-z]/', $this->data)) {
            throw new ValidationFailedException("Value must contain a lower case. ");
        }

        if(isset($number) && !preg_match('/\d/', $this->data)) {
            throw new ValidationFailedException("Value must contain a numeric character. ");
        }

        if(isset($symbol) && !preg_match('/[^a-zA-Z\d]/', $this->data)) {
            throw new ValidationFailedException("Value must contain a symbol. ");
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

        if(isset($internationalize) && mb_substr($this->data, 0, 2) !== '00' && mb_substr($this->data, 0, 1) !== '+') {
            throw new ValidationFailedException("Value must be in international format. ");
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
     * @throws ValidationFailedException
     */
    private function radioField(RadioField $field): RadioField
    {
        $field->setCleanedData($this->validateChoiceOptions($field));
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
        $this->checkMaxLength($field->getProperty('maxLength'), $this->data);

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
        $this->checkMaxLength($field->getProperty('maxLength'), $this->data);

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

        if (empty(strtotime($this->data))) {
            throw new ValidationFailedException('Invalid time given.');
        }

        $this->validateDate($field);

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
