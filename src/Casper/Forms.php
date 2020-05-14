<?php


namespace Casper;


use Casper\Exceptions\FormNotValidatedException;
use Casper\Exceptions\InvalidMethodException;
use Casper\Exceptions\InvalidUrlException;
use Casper\Exceptions\ValidationFailedException;
use Casper\Fields\BaseField;
use Casper\Fields\ButtonField;
use Casper\Fields\Fields;
use Casper\Fields\FileField;
use Exception;

abstract class Forms
{
    private array $errors;
    private array $data;
    private array $initial;
    private array $cleanedData;
    private bool $validated;
    private bool $isValid;
    private string $formUrlPath;
    private string $method;
    private ?string $style;
    protected FormFields $fields;


    /**
     * Form constructor.
     * @throws InvalidMethodException
     * @throws InvalidUrlException
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
    public function setInitialData(array $data): Forms
    {
        $this->initial = $data;
        foreach (get_object_vars($this) as $key => $var)
        {
            if($var instanceof Fields)
            {
                if(array_key_exists($key,$data))
                {
                    $var->initial($data[$key]);
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
     */
    private function validateFormCreate()
    {
        if(!isset($this->formUrlPath))
            throw new InvalidUrlException();

        if(!isset($this->method))
            throw new InvalidMethodException();
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
            return "<div class='{$this->getStyle()}'><br><form action='{$this->getUrl()}' method='{$this->getMethod()}' enctype='multipart/form-data' >";
        }
        return "<div class='{$this->getStyle()}'><br><form action='{$this->getUrl()}' method='{$this->getMethod()}' >";
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

        foreach (get_object_vars($this) as $key => $var)
        {
            if($var instanceof BaseField)
            {
                if($var instanceof ButtonField)
                {
                    $response->hasSubmit = true;
                }

                if($var instanceof FileField)
                {
                    $response->hasFile = true;
                }

                $response->htmlBody .= $var->asHtml($key);
            }
        }

        return $response;
    }

    /**
     * @param bool $hasSubmit
     * @return string
     */
    private function getHtmlBase(bool $hasSubmit = false)
    {
        if($hasSubmit === true)
        {
            return "<br><div><input type='submit' value='Submit'></div><br></form></div>";
        }
        return "<br></form></div>";
    }

    public function asP()
    {

    }

    public function asTable()
    {

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
                    $this->errors[$key] = $validationError;
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
}