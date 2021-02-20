<?php


namespace Casper\Fields;


use Casper\Exceptions\ValidationFailedException;

class TextField extends Fields
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
     * @var int
     */
    protected int $cols;
    /**
     * @var int
     */
    protected int $rows;

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
     * @param int $cols
     * @return $this
     */
    public function cols(int $cols): self
    {
        $this->cols = $cols;
        return  $this;
    }

    /**
     * @param int $rows
     * @return $this
     */
    public function rows(int $rows): self
    {
        $this->rows = $rows;
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
        $res['cols'] = $this->getProperty('cols');
        $res['rows'] = $this->getProperty('rows');
        return $res;
    }
    /**
     * @param string $name
     * @return string
     */
    public function asHtml(string $name=''): string
    {
        $res = parent::asHtml($name);
        $fieldHtml = $this->getFieldHtml();
        $field = "<textarea {$fieldHtml} ";
        if(!empty($this->minLength)){
            $field .= "minlength='{$this->minLength}' ";
        }

        if(!empty($this->maxLength)){
            $field .= "maxlength='{$this->maxLength}' ";
        }

        if(!empty($this->cols)){
            $field .= " cols='{$this->cols}' ";
        }
        if(!empty($this->rows)){
            $field .= " rows='{$this->rows}' ";
        }
        $field .= " ></textarea>";

        $res = str_replace('htmlField', $field, $res);
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
                if(isset($this->regex)){
                    $this->checkRegex($this->regex, $this->data);
                }
                if(isset($this->minLength)){
                    $this->checkMinLength($this->minLength, $this->data);
                }
                if(isset($this->maxLength)){
                    $this->checkMaxLength($this->maxLength, $this->data);
                }
                $this->setCleanedData((string) $this->data);
            }

            $this->isValid = true;
            return null;

        }catch (ValidationFailedException $validationFailedException){
            $this->isValid = false;
            return $validationFailedException->getMessage();
        }
    }
}