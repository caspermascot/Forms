<?php


namespace Casper;


use Casper\Exceptions\FormNotValidatedException;
use Casper\Exceptions\InvalidMethodException;
use Casper\Exceptions\InvalidUrlException;
use Casper\Exceptions\ValidationFailedException;
use Casper\Fields\BaseField;
use Casper\Fields\BooleanField;
use Casper\Fields\ButtonField;
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
use Casper\Fields\ResetButtonField;
use Casper\Fields\SlugField;
use Casper\Fields\SubmitButtonField;
use Casper\Fields\TextField;
use Casper\Fields\TimeField;
use Casper\Fields\UrlField;
use Casper\Fields\UuidField;
use Exception;

abstract class Forms
{
    /**
     * @var array
     */
    private array $errors;
    /**
     * @var array
     */
    private array $data;
    /**
     * @var array
     */
    private array $default;
    /**
     * @var array
     */
    private array $cleanedData;
    /**
     * @var bool
     */
    private bool $validated;
    /**
     * @var bool
     */
    private bool $isValid;
    /**
     * @var string
     */
    private string $formUrlPath;
    /**
     * @var string
     */
    private string $method;
    /**
     * @var string|null
     */
    private ?string $style;
    /**
     * @var string
     */
    private string $formName;
    /**
     * @var FormFields
     */
    protected FormFields $fields;


    /**
     * Form constructor.
     * @throws InvalidMethodException
     * @throws InvalidUrlException
     * @throws Exceptions\FieldCreateFailedException
     */
    public function __construct()
    {
        $this->fields = new FormFields();
        $this->build();
        $this->setUrl('');
        $this->setMethod('post');
        $this->validateFormCreate();
    }


    /**
     * @param array $data
     * @return Forms
     */
    public function setDefaultData(array $data): Forms
    {
        $this->default = $data;
        foreach (get_object_vars($this) as $key => $var)
        {
            if($var instanceof Fields)
            {
                if(array_key_exists($key,$data))
                {
                    $var->default($data[$key]);
                }
            }
        }
        return $this;
    }

    /**
     * @param array $data
     * @return Forms
     * @throws Exception
     */
    public function setData(array $data): Forms
    {
        $this->data = $data;
        foreach (get_object_vars($this) as $key => $var)
        {
            if($var instanceof Fields)
            {
                if(array_key_exists($key, $data))
                {
                    $var->setName($key);
                    $var->setData($data[$key]);
                }
            }
        }
        $this->validate();
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return isset($this->data) ? $this->data : [];
    }

    /**
     * return null
     */
    abstract protected function build(): void;

    /**
     * @param string $formUrlPath
     */
    protected function setUrl(string $url): void
    {
        if(!isset($this->formUrlPath))
        {
            $this->formUrlPath = $url;
        }
    }

    /**
     * @param string $method
     * @throws InvalidMethodException
     */
    protected function setMethod(string $method='post')
    {
        if(!in_array(strtolower($method), ['get', 'post']))
            throw new InvalidMethodException();

        if(!isset($this->method))
        {
            $this->method = $method;
        }
    }

    /**
     * @throws InvalidMethodException
     * @throws InvalidUrlException
     * @throws Exceptions\FieldCreateFailedException
     */
    private function validateFormCreate()
    {
        if(!isset($this->formUrlPath))
            throw new InvalidUrlException();

        if(!isset($this->method))
            throw new InvalidMethodException();
        $this->formName = FormUtils::getFieldType($this);
        foreach (get_object_vars($this) as $key => $var)
        {
            if($var instanceof BaseField)
            {
                $var->setName($key);
                $var->validateFieldCreate($this->formName);
            }
        }
    }

    /**
     * @return string
     */
    private function getStyle()
    {
        return isset($this->style) ? $this->style : '';
    }

    /**
     * @return string
     */
    private function getUrl()
    {
        return $this->formUrlPath;
    }

    /**
     * @return string
     */
    private function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function asHtml(): string
    {
        $htmlBody = $this->getHtmlBody();
        return $this->getHtmlHeader($htmlBody->hasFile).$htmlBody->htmlBody.$this->getHtmlBase($htmlBody->hasSubmit);
    }

    /**
     * @param bool $hasFile
     * @return string
     */
    private function getHtmlHeader(bool $hasFile = false)
    {
        if($hasFile === true )
        {
            return "<div class='{$this->getStyle()}'><form class='{$this->getStyle()}' name='{$this->formName}' id='id_{$this->formName}' action='{$this->getUrl()}' method='{$this->getMethod()}' enctype='multipart/form-data' >";
        }
        return "<div class='{$this->getStyle()}'><form class='{$this->getStyle()}' name='{$this->formName}' id='id_{$this->formName}' action='{$this->getUrl()}' method='{$this->getMethod()}' >";
    }

    /**
     * @return object
     */
    private function getHtmlBody(): object
    {
        $response = (object) [
            'hasFile' => false,
            'hasSubmit' => false,
            'htmlBody' => ''
        ];
        $tail = '';

        foreach (get_object_vars($this) as $key => $var){
            if($var instanceof BaseField){
                if($var instanceof ButtonField){
                    $tail .= $var->asHtml($key);
                    if($var instanceof SubmitButtonField){
                        $response->hasSubmit = true;
                    }
                }
                else{
                    $response->htmlBody .= $var->asHtml($key);
                }

                if($var instanceof FileField){
                    $response->hasFile = true;
                }
            }
        }

        $response->htmlBody .= $tail;
        return $response;
    }

    /**
     * @param bool $hasSubmit
     * @return string
     */
    private function getHtmlBase(bool $hasSubmit = false)
    {
        if($hasSubmit === true){
            return "</form></div>";
        }
        return "<div> <span><input type='submit' value='Submit'></span></div><br></form></div>";
    }

    /**
     *
     */
    public function asP()
    {

    }

    /**
     *
     */
    public function asTable()
    {

    }

    /**
     * @return array
     */
    public function asJson()
    {
        $response = [];
        foreach (get_object_vars($this) as $key => $var)
        {
            if($var instanceof BaseField)
            {
                $response[$key] = $var->asJson();
            }
        }

        return $response;
    }

    /**
     * @throws Exception
     */
    private function validate(): void
    {
        $this->isValid = true;
        $this->validated = true;
        foreach (get_object_vars($this) as $key => $var){
            $validationError = null;
            if($var instanceof Fields){
                $validationError = $var->validate();
                if(is_null($validationError)){
                    if (method_exists($this, "validate_".$key)){
                        try{
                            $cleanedData = call_user_func([$this, "validate_".$key], []);
                            $var->setCleanedData($cleanedData);
                        }
                        catch (ValidationFailedException $exception){
                            $validationError = $exception->getMessage();
                        }
                        catch (Exception $exception){
                            throw new Exception($exception->getMessage());
                        }
                    }
                }

                if(empty($validationError)){
                    $this->cleanedData[$key] = $var->getProperty('cleanedData');
                }
                else{
                    $customErrorMessage = $var->getProperty('customErrorMessage');
                    $this->errors[$key] = empty($customErrorMessage) ? $validationError : $customErrorMessage;
                }
            }
        }

        if(empty($this->errors))
        {
            $this->isValid = true;
        }
        else
        {
            $this->isValid = false;
            $this->cleanedData = [];
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isValid(): bool
    {
        $this->validate();
        return $this->isValid;
    }

    /**
     * @return array
     * @throws FormNotValidatedException
     */
    public function getCleanedData()
    {
        if(!isset($this->validated)){
            throw new FormNotValidatedException();
        }

        return isset($this->cleanedData) ? $this->cleanedData : [];
    }

    /**
     * @return array
     * @throws FormNotValidatedException
     */
    public function getErrors()
    {
        if(!isset($this->validated)){
            throw new FormNotValidatedException();
        }

        return isset($this->errors) ? $this->errors : [];
    }

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
}