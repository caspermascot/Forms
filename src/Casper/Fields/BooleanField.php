<?php


namespace Casper\Fields;


use Casper\Exceptions\ValidationFailedException;

class BooleanField extends CharField
{
    private const booleanErrorMessage = 'Invalid boolean value';

    public function validate(): ?string
    {
        try {
            $this->checkRequired($this->data);
            $this->checkNull($this->data);
            $this->checkBlank($this->data);

            $options = [
                'true' => true,
                'false' => false,
                'yes' => true,
                'no' => false,
                'ok' => true,
                '1' => true,
                '0' => false
            ];

            if($this->allowNull && empty($this->data)){
                $this->isValid = true;
                return null;
            }

            if(!array_key_exists((string)$this->data, $options) && !is_bool($this->data)){
                throw new ValidationFailedException(self::booleanErrorMessage);
            }

            $this->isValid = true;
            $this->setCleanedData((bool) $options[$this->data]);
            return null;

        }catch (ValidationFailedException $validationFailedException){
                $this->isValid = false;
            $this->setValidationErrorMessage($validationFailedException->getMessage());
            return $validationFailedException->getMessage();
        }
    }
}