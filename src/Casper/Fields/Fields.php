<?php


namespace Casper\Fields;


use Casper\Exceptions\ValidationFailedException;
use Casper\Validators\Validator;
use Casper\Validators\ValidatorsInterface;

class Fields extends BaseField
{
    protected $data;
    protected $initial;
    protected $cleanedData;
    protected ?bool $validated;
    protected ?bool $required = false;
    protected ?bool $allowNull = true;
    protected ?bool $allowBlank = true;
    protected ?string $label;
    protected ?string $placeHolder;
    protected ?string $helpText;
    protected ?bool $disabled;
    protected ?bool $hidden;
    protected ?string $style;
    protected ?bool $autoFocus;
    protected ?bool $autoComplete;
    protected ?bool $readOnly;
    protected ?string $errorMessage;
    protected ?string $regex;
    protected ?ValidatorsInterface $validator;
    protected ?array $customErrorMessages;

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
    public function initial($data): self
    {
        $this->initial = $data;
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
     * @param array $customErrorMessages
     * @return self
     */
    public function customErrorMessages(array $customErrorMessages): self
    {
        $this->customErrorMessages = $customErrorMessages;
        return $this;
    }

    protected function validate(): ?string
    {
        $this->validated = true;
        if(!isset($this->validator)){
            $this->validator = new Validator();
        }

        if($this->validator instanceof ValidatorsInterface){
            try{
                 $this->validator->run($this);

            }catch (ValidationFailedException $validationFailedException)
            {
                return $validationFailedException->getMessage();
            }
        }
        return null;
    }
}