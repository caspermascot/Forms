<?php


namespace Casper\Fields;


use Casper\Exceptions\ValidationFailedException;

class PhoneField extends CharField
{

    private const phoneFormat = "/^\s*(?:\+?(\d{1,3}))?([-. (]*(\d{3})[-. )]*)?((\d{3})[-. ]*(\d{2,4})(?:[-.x ]*(\d+))?)\s*$/";
    private const internationalFormatErrorMessage = 'Value must be in international format. ';
    private const invalidPhoneErrorMessage = 'Invalid phone format. ';


    protected bool $internationalize;

    /**
     * @param bool $internationalize
     * @return $this
     */
    public function internationalFormat(bool $internationalize): self
    {
        $this->internationalize = $internationalize;
        return $this;
    }

    /**
     * @return array
     */
    public function asJson(): array
    {
        $res = parent::asJson();
        $res['internationalize'] = !empty($this->getProperty('internationalize'));
        return $res;
    }

    /**
     * @param string $name
     * @return string
     */
    public function asHtml(string $name=''): string
    {
        $res = parent::asHtml($name);
        $res = str_replace("type='text'", "type='tel'", $res);
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
                if(isset($this->internationalize)
                    && mb_substr($this->data, 0, 2) !== '00'
                    && mb_substr($this->data, 0, 1) !== '+') {
                    throw new ValidationFailedException(self::internationalFormatErrorMessage);
                }

                if (!preg_match(self::phoneFormat, $this->data)) {
                    throw new ValidationFailedException(self::invalidPhoneErrorMessage);
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

}