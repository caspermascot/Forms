<?php


namespace Casper\Fields;


use Casper\Exceptions\ValidationFailedException;

class UuidField extends CharField
{
    private const uuidRegex = "/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i";
    private const invalidUuidErrorMessage = "Invalid uuid. ";
    public function validate(): ?string
    {
        try {
            $this->checkRequired($this->data);
            $this->checkNull($this->data);
            $this->checkBlank($this->data);
            $this->checkEmpty($this->data);

            if(!empty($this->data)){
                $this->baseCharFieldCheck();
                if (!preg_match(self::uuidRegex, $this->data)) {
                    throw new ValidationFailedException(self::invalidUuidErrorMessage);
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