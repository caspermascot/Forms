<?php


namespace Casper\Fields;


use Casper\Exceptions\ValidationFailedException;

class TimeField extends DateField
{
    private const timeRegex = "/^([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/i";
    private const invalidTimeErrorMessage = "Invalid time given. ";
    /**
     * @param string $name
     * @return string
     */
    public function asHtml(string $name=''): string
    {
        $res = parent::asHtml($name);
        $res = str_replace("type='date'", "type='time'", $res);
        return $res;
    }

    /**
     * @return array
     */
    public function asJsonSchema(): array
    {
        $res = parent::asJsonSchema();
        $res['format'] = 'time';

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
                    throw new ValidationFailedException(self::invalidTimeErrorMessage);
                }
                if (!preg_match(self::timeRegex, $this->data)) {
                    throw new ValidationFailedException(self::invalidTimeErrorMessage);
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

    /**
     * @param $data
     * @return Fields
     */
    public function setCleanedData($data): Fields
    {
        $this->cleanedData = date('H:i:s', strtotime($data));
        return $this;
    }
}