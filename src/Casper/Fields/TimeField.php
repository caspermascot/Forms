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
                $this->checkMinDate($this->minValue, $this->data);
                $this->checkMaxDate($this->maxValue, $this->data);
                $this->setCleanedData((string) $this->data);
            }

            $this->isValid = true;
            return null;

        }catch (ValidationFailedException $validationFailedException){
            $this->isValid = false;
            return $validationFailedException->getMessage();
        }
    }

    public function setCleanedData($data): Fields
    {
        return parent::setCleanedData(date('H:i:s', strtotime($this->data)));
    }
}