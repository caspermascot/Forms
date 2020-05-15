<?php


namespace Casper\Fields;


use Casper\Exceptions\FieldCreateFailedException;
use Casper\Exceptions\ValidationFailedException;
use Casper\Validators\Validator;
use Casper\Validators\ValidatorsInterface;

class Fields extends BaseField
{
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
     * @var string
     */
    protected string $name;
    /**
     * @var bool|null
     */
    protected ?bool $validated;
    /**
     * @var bool|null
     */
    protected ?bool $required = false;
    /**
     * @var bool|null
     */
    protected ?bool $allowNull = true;
    /**
     * @var bool|null
     */
    protected ?bool $allowBlank = true;
    /**
     * @var string|null
     */
    protected ?string $label;
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
    protected ?string $regex;
    /**
     * @var ValidatorsInterface|null
     */
    protected ?ValidatorsInterface $validator;
    /**
     * @var string
     */
    protected string $customErrorMessage;

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
     * @param $data
     * @return self
     */
    public function default($data): self
    {
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
     * @param string $name
     * @return $this
     */
    protected function setName(string $name): self
    {
        $this->name = $name;
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
     * @param string $label
     * @return self
     */
    public function label(string $label): self
    {
        $this->label = $label;
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

    /**
     * @return string|null
     */
    protected function validate(): ?string
    {
        $this->validated = true;
        if(!isset($this->validator)){
            $this->validator = new Validator();
        }

        if($this->validator instanceof ValidatorsInterface){
            try{
                 $this->validator->run($this);

            }catch (ValidationFailedException $validationFailedException){
                return $validationFailedException->getMessage();
            }
        }
        return null;
    }

    /**
     * @param string $caller
     * @throws FieldCreateFailedException
     */
    protected function validateFieldCreate(string $caller): void
    {
        if(!empty($this->required) and !empty($this->default)){
            throw new FieldCreateFailedException($this->getProperty('name'), $caller);
        }
    }
}