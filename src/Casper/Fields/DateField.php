<?php


namespace Casper\Fields;


use Casper\Exceptions\ValidationFailedException;

class DateField extends Fields
{

    /**
     * @var string
     */
    protected string $minValue;
    /**
     * @var string
     */
    protected string $maxValue;

    /**
     * @param string $minValue
     * @return DateField
     */
    public function minValue(string $minValue): self
    {
        $this->minValue = $minValue;
        return $this;
    }

    /**
     * @param string $maxValue
     * @return DateField
     */
    public function maxValue(string $maxValue): self
    {
        $this->maxValue = $maxValue;
        return $this;
    }
    /**
     * @param string $name
     * @return string
     */
    public function asHtml(string $name=''): string
    {
        $replacement = "type='date' ";
        if(!empty($this->minValue)){
            $replacement .= "min='{$this->minValue}' ";
        }

        if(!empty($this->maxValue)){
            $replacement .= "max='{$this->maxValue}' ";
        }
        $res = parent::asHtml($name);
        $res = str_replace("type='text'", $replacement, $res);
        return $res;
    }
    /**
     * @return array
     */
    public function asJson(): array
    {
        $res = parent::asJson();
        $res['minValue'] = $this->getProperty('minValue');
        $res['maxValue'] = $this->getProperty('maxValue');
        return $res;
    }

    /**
     * @return array
     */
    public function asJsonSchema(): array
    {
        $res = parent::asJsonSchema();
        $res['type'] = 'string';
        $res['format'] = 'date';

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
                if(empty(strtotime($this->data))){
                    throw new ValidationFailedException('Invalid date Value');
                }
                if(isset($this->minValue)){
                    $this->checkMinDate($this->minValue, $this->data);
                }
                if(isset($this->maxValue)){
                    $this->checkMaxDate($this->maxValue, $this->data);
                }
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

    public function setCleanedData($data): Fields
    {
        return parent::setCleanedData(date('Y-m-d', strtotime($this->data)));
    }

}