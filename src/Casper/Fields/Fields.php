<?php


namespace Casper\Fields;


use Casper\Exceptions\ValidationFailedException;
use Casper\Validators\ValidatorsInterface;
use Exception;

abstract class Fields extends BaseField
{
    private const requiredErrorMessage = "This is required. ";
    private const allowNullErrorMessage = "This cannot be null. ";
    private const allowBlankErrorMessage = "This cannot be empty. ";
    private const regexErrorMessage = "The given value does not match the pattern %s ";
    private const minValueErrorMessage = "Value cannot be less than %s ";
    private const maxValueErrorMessage = "Value cannot be greater than %s ";
    private const minLengthErrorMessage = "Length cannot be less than %s ";
    private const maxLengthErrorMessage = "Length cannot be more than %s ";
    private const invalidChoiceErrorMessage = "%s is not a valid option for this field, supported options are [ %s ] ";

    /**
     * @var
     */
    protected $data;
    /**
     * @var
     */
    protected $default;
    /**
     * @var
     */
    protected $cleanedData;
    /**
     * @var bool|null
     */
    protected ?bool $validated;
    /**
     * @var bool|null
     */
    protected ?bool $required = true;
    /**
     * @var bool|null
     */
    protected ?bool $allowNull = false;
    /**
     * @var bool|null
     */
    protected ?bool $allowBlank = false;
    /**
     * @var string|null
     */
    protected ?string $placeHolder;
    /**
     * @var string|null
     */
    protected ?string $helpText;
    /**
     * @var bool|null
     */
    protected ?bool $disabled;
    /**
     * @var bool|null
     */
    protected ?bool $hidden;
    /**
     * @var string|null
     */
    protected ?string $style;
    /**
     * @var bool|null
     */
    protected ?bool $autoFocus;
    /**
     * @var bool|null
     */
    protected ?bool $autoComplete;
    /**
     * @var bool|null
     */
    protected ?bool $readOnly;
    /**
     * @var string|null
     */
    protected ?string $errorMessage;
    /**
     * @var string|null
     */
    protected ?string $regex;# = '(.*?)';
    /**
     * @var ValidatorsInterface|null
     */
    protected ?ValidatorsInterface $validator;
    /**
     * @var string
     */
    protected string $customErrorMessage;
    /**
     * @var bool
     */
    protected bool $isValid;

    /**
     * @param $data
     * @return self
     */
    public function setData($data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getData()
    {
        if(isset($this->isValid) && $this->isValid){
            return $this->data();
        }
        $data = $this->getProperty('data');
        // perhaps look in $_FILES
        if(empty($data) && ($this instanceof FileField || $this instanceof ImageField)) {
            $name = $this->getProperty('name');
            if(!empty($name) && !empty($_FILES[$name]['name'][0])) {
                $data = $_FILES[$name];
                $this->setData($data);
            }
        }
        return $data;
    }

    /**
     * @param null $data
     * @return mixed|null
     */
    public function data($data=null)
    {
        if(!is_null($data)){
            $this->setData($data);
        }
        $this->validate();
        return $this->getProperty('cleanedData');
    }

    /**
     * @param $data
     * @return self
     */
    public function default($data): self
    {
        $this->required = false;
        $this->default = $data;
        $this->setData($data);
        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    protected function setCleanedData($data): self
    {
        $this->cleanedData = $data;
        return $this;
    }

    /**
     * @param $message
     * @return $this
     */
    protected function setErrorMessage($message): self
    {
        $this->errorMessage = $message;
        return $this;
    }

    /**
     * @param bool $disable
     * @return self
     */
    public function disabled(bool $disable): self
    {
        $this->disabled = $disable;
        return $this;
    }

    /**
     * @param bool $required
     * @return self
     */
    public function required(bool $required): self
    {
        $this->required = $required;
        return $this;
    }

    /**
     * @param bool $allowBlank
     * @return $this
     */
    public function allowBlank(bool $allowBlank): self
    {
        $this->allowBlank = $allowBlank;
        return $this;
    }

    /**
     * @param bool $allowNull
     * @return self
     */
    public function allowNull(bool $allowNull): self
    {
        $this->allowNull = $allowNull;
        return $this;
    }

    /**
     * @param string $helpText
     * @return self
     */
    public function helpText(string $helpText): self
    {
        $this->helpText = $helpText;
        return $this;
    }

    /**
     * @param string $placeHolder
     * @return self
     */
    public function placeHolder(string $placeHolder): self
    {
        $this->placeHolder = $placeHolder;
        return $this;
    }

    /**
     * @param bool $autoFocus
     * @return self
     */
    public function autoFocus(bool $autoFocus): self
    {
        $this->autoFocus = $autoFocus;
        return $this;
    }

    /**
     * @param bool $autoComplete
     * @return self
     */
    public function autoComplete(bool $autoComplete): self
    {
        $this->autoComplete = $autoComplete;
        return $this;
    }

    /**
     * @param bool $hidden
     * @return self
     */
    public function hidden(bool $hidden): self
    {
        $this->hidden = $hidden;
        return $this;
    }

    /**
     * @param bool $readOnly
     * @return self
     */
    public function readOnly(bool $readOnly): self
    {
        $this->readOnly = $readOnly;
        return $this;
    }

    /**
     * @param string $pattern
     * @return self
     */
    public function regex(string $pattern): self
    {
        $this->regex = $pattern;
        return $this;
    }

    /**
     * @param ValidatorsInterface $validator
     * @return self
     */
    public function validator(ValidatorsInterface $validator): self
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * @param string $customErrorMessage
     * @return self
     */
    public function customErrorMessages(string $customErrorMessage): self
    {
        $this->customErrorMessage = $customErrorMessage;
        return $this;
    }

    abstract public function validate(): ?string;

    /**
     * @param string $errorMessage
     */
    protected function setValidationErrorMessage(string $errorMessage): void
    {
        if(isset($this->customErrorMessage)){
            $this->setErrorMessage($this->customErrorMessage);
        }
        $this->setErrorMessage($errorMessage);
    }

    /**
     *
     */
    public function runCustomValidation(): ?string
    {
        if(isset($this->validator)) {
            $this->validator->run($this);
        }

        if(isset($this->validator) && $this->validator instanceof ValidatorsInterface){
            try{
                 $this->validator->run($this);
                 $this->isValid = true;
            }catch (Exception $validationFailedException){
                $this->isValid = false;
                return $validationFailedException->getMessage();
            }
        }
        return null;
    }

    /**
     * @param string $name
     * @return string
     */
    public function asHtml(string $name=''): string
    {
        $res = parent::asHtml($name);
        $label = ucfirst($this->getProperty('label'));
        $res = str_replace('htmlLabel', "<label class='{$this->getProperty('style')}' for='{$name}'>{$label}</label> <br> ", $res);

        if(!empty($this->getProperty('helpText')) && $this->getProperty('isValid') === false) {
            $res = str_replace('helpText', "<br><span class=''>{$this->getProperty('helpText')}</span>", $res);
        } else{
            $res = str_replace('helpText', '', $res);
        }

        $flag = true;
        if($this instanceof Choices || $this instanceof TextField){
            $flag = false;
        }

        if($flag){
            $fieldHtml = $this->getFieldHtml();
            $field = "<input type='text' {$fieldHtml}/>";
            $res = str_replace('htmlField', $field, $res);
        }
        return $res;
    }

    /**
     * @return array
     */
    public function asJson(): array
    {
        $res = parent::asJson();
        $res['required'] = !empty($this->getProperty('required'));
        $res['allowNull'] = !empty($this->getProperty('allowNull'));
        $res['allowBlank'] = !empty($this->getProperty('allowBlank'));
        $res['hidden'] = !empty($this->getProperty('hidden'));
        $res['disabled'] = !empty($this->getProperty('disabled'));
        $res['autoComplete'] = !empty($this->getProperty('autoComplete'));
        $res['autoFocus'] = !empty($this->getProperty('autoFocus'));
        $res['regex'] = $this->getProperty('regex');
        $res['default'] = $this->getProperty('default');
        $res['helpText'] = $this->getProperty('helpText');
        $res['placeHolder'] = $this->getProperty('PlaceHolder');

        return $res;
    }

    /**
     * @return string
     */
    protected function getFieldHtml(): string
    {
        $res = " class='{$this->getProperty('style')}' ";
        if(!empty($this->required)){
            $res .= 'required="true" ';
        }

        if(!empty($this->autoComplete)){
            $res .= 'autocomplete="on" ';
        }

        if(!empty($this->disabled)){
            $res .= 'disabled="true" ';
        }

        if(!empty($this->readOnly)){
            $res .= 'readonly="true" ';
        }

        if(!empty($this->autoFocus)){
            $res .= 'autofocus="true" ';
        }

        if(!empty($this->data) && !is_array($this->data)) {
            $res .= "value='{$this->data}' ";
        }

        if(!empty($this->placeHolder)){
            $res .= "placeholder='{$this->placeHolder}' ";
        }

        if(!empty($this->regex)){
            $res .= "pattern='{$this->regex}' ";
        }

        if(!empty($this->name)){
            $res .= " name='{$this->name}' ";
            $res .= " id='id_{$this->name}' ";
        }

        return $res;
    }

    /**
     * @return array
     */
    public function asJsonSchema(): array
    {
        $response = [
            'type' => 'string',
        ];

        if(!empty($this->regex)){
            $response['pattern'] = $this->regex;
        }

        if(!empty($this->placeHolder)){
            $response['description'] = $this->placeHolder;
        }

        if(!empty($this->label)){
            $response['label'] = $this->label;
        }

        if(!empty($this->readOnly)){
            $response['readOnly'] = $this->readOnly;
        }

        if(!empty($this->helpText)){
            $response['example'] = $this->helpText;
        }

        if(!empty($this->data) && !is_array($this->data)) {
            $response['default'] = $this->data;
        }

        return $response;
    }

//    function __toString(): ?string
//    {
//        return json_encode($this->getData());
//    }


    /**
     * @param $value
     * @throws ValidationFailedException
     */
    protected function checkNull($value): void
    {
        if($this->allowNull === false && is_null($value) ){
            throw new ValidationFailedException(self::allowNullErrorMessage);
        }
    }

    /**
     * @param $value
     * @throws ValidationFailedException
     */
    protected function checkBlank($value): void
    {
        if($this->allowBlank === false && $value === '' ) {
            throw new ValidationFailedException(self::allowBlankErrorMessage);
        }
    }

    /**
     * @param $value
     * @throws ValidationFailedException
     */
    protected function checkEmpty($value): void
    {
        if($this->required === true && empty($value) ) {
            throw new ValidationFailedException(self::requiredErrorMessage);
        }
    }

    /**
     * @param $value
     * @throws ValidationFailedException
     */
    protected function checkRequired($value): void
    {
        if($this->required === true && !isset($value) ) {
            throw new ValidationFailedException(self::requiredErrorMessage);
        }
    }

    /**
     * @param string $regex
     * @param string $value
     * @throws ValidationFailedException
     */
    protected function checkRegex(string $regex, string $value): void
    {
        $regex = '/'.trim($regex,'/').'/';
        if (is_string($regex) && !preg_match(($regex), $value)) {
            throw new ValidationFailedException(sprintf(self::regexErrorMessage,$regex));
        }
    }


    /**
     * @param $minValue
     * @param $data
     * @throws ValidationFailedException
     */
    protected function checkMinValue($minValue, $data): void
    {
        if($data < $minValue) {
            throw new ValidationFailedException(sprintf(self::minValueErrorMessage,$minValue));
        }
    }

    /**
     * @param $maxValue
     * @param $data
     * @throws ValidationFailedException
     */
    protected function checkMaxValue($maxValue, $data): void
    {
        if($data > $maxValue) {
            throw new ValidationFailedException(sprintf(self::maxValueErrorMessage,$maxValue));
        }
    }

    /**
     * @param $minLength
     * @param $data
     * @throws ValidationFailedException
     */
    protected function checkMinLength($minLength, $data): void
    {
        if(strlen($data) < $minLength) {
            throw new ValidationFailedException(sprintf(self::minLengthErrorMessage, $minLength));
        }
    }

    /**
     * @param $maxLength
     * @param $data
     * @throws ValidationFailedException
     */
    protected function checkMaxLength($maxLength, $data): void
    {
        if(strlen($data) > $maxLength) {
            throw new ValidationFailedException(sprintf(self::maxLengthErrorMessage, $maxLength));
        }
    }

    /**
     * @param $choices
     * @param $data
     * @return string|null
     * @throws ValidationFailedException
     */
    protected function validateChoiceOptions($choices, $data): ?string
    {
        $choiceOptions = is_array($data) ? $data : explode(',',$data);

        if(isset($choices)){
            foreach ($choiceOptions as $key => $value){
                if(!in_array($value, $choices, true)){
                    throw new ValidationFailedException(sprintf(self::invalidChoiceErrorMessage,$value, implode(', ',$choices)));
                }
            }
        }

        return implode(',',$choiceOptions);
    }

    /**
     * @param $minDate
     * @param $value
     * @throws ValidationFailedException
     */
    protected function checkMinDate($minDate, $value): void
    {
        if(strtotime($minDate) < strtotime($value)) {
            throw new ValidationFailedException(sprintf(self::minValueErrorMessage, $minDate));
        }
    }

    /**
     * @param $maxDate
     * @param $value
     * @throws ValidationFailedException
     */
    protected function checkMaxDate($maxDate, $value): void
    {
        if(strtotime($value) > strtotime($maxDate)) {
            throw new ValidationFailedException(sprintf(self::maxValueErrorMessage, $maxDate));
        }
    }
}
