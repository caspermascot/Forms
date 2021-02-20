<?php


namespace Casper\Fields;


use Casper\Exceptions\ValidationFailedException;

class BooleanField extends CharField
{

    public function validate(): ?string
    {
        try {
            $this->checkRequired($this->data);
            $this->checkNull($this->data);
            $this->checkBlank($this->data);

            $options = [
                true => true,
                false => false,
                'yes' => true,
                'no' => false,
                'ok' => true,
                '1' => true,
                '0' => false
            ];
            if(!array_key_exists((string)$this->data, $options)){
                throw new ValidationFailedException('Invalid boolean value');
            }

            $this->isValid = true;
            $this->setCleanedData((bool) $options[$this->data]);
            return null;

        }catch (ValidationFailedException $validationFailedException){
                $this->isValid = false;
                return $validationFailedException->getMessage();
        }
    }
}