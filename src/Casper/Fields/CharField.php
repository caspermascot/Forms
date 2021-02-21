<?php


namespace Casper\Fields;


use Casper\Exceptions\ValidationFailedException;

class CharField extends Fields
{
    /**
     * @var int
     */
    protected int $minLength;
    /**
     * @var int
     */
    protected int $maxLength;

    /**
     * @param int $minLength
     * @return $this
     */
    public function minLength(int $minLength): self
    {
        $this->minLength = $minLength;
        return $this;
    }

    /**
     * @param int $maxLength
     * @return $this
     */
    public function maxLength(int $maxLength): self
    {
        $this->maxLength = $maxLength;
        return $this;
    }

    /**
     * @return array
     */
    public function asJson(): array
    {
        $res = parent::asJson();
        $res['minLength'] = $this->getProperty('minLength');
        $res['maxLength'] = $this->getProperty('maxLength');
        return $res;
    }

    /**
     * @return array
     */
    public function asJsonSchema(): array
    {
        $res = parent::asJsonSchema();
        if(!empty($this->getProperty('minLength'))){
            $res['minLength'] = $this->getProperty('minLength');
        }

        if(!empty($this->getProperty('maxLength'))){
            $res['maxLength'] = $this->getProperty('maxLength');
        }
        return $res;
    }

    /**
     * @param string $name
     * @return string
     */
    public function asHtml(string $name=''): string
    {
        $replacement = "type='text' ";
        if(!empty($this->minLength)){
            $replacement .= sprintf("minlength='%d' ", $this->minLength);
        }

        if(!empty($this->maxLength)){
            $replacement .= sprintf("maxlength='%d' ", $this->maxLength);
        }
        $res = parent::asHtml($name);
        $res = str_replace("type='text'", $replacement, $res);
        return $res;
    }

    public function validate(): ?string
    {
        try {
            $this->checkRequired($this->data);
            $this->checkNull($this->data);
            $this->checkBlank($this->data);
            $this->checkEmpty($this->data);

            if(!empty($this->data)){
                $this->baseCharFieldCheck();
                $this->setCleanedData((string) $this->data);
            }

            $this->isValid = true;
            return null;

        }catch (ValidationFailedException $validationFailedException){
            $this->isValid = false;
            $this->setValidationErrorMessage($validationFailedException->getMessage());
            return $validationFailedException->getMessage();
        }
    }

    /**
     * @throws ValidationFailedException
     */
    protected function baseCharFieldCheck(): void
    {
        if(isset($this->regex)){
            $this->checkRegex($this->regex, $this->data);
        }
        if(isset($this->minLength)){
            $this->checkMinLength($this->minLength, $this->data);
        }
        if(isset($this->maxLength)){
            $this->checkMaxLength($this->maxLength, $this->data);
        }
    }
}