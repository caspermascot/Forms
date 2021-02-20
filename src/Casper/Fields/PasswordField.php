<?php


namespace Casper\Fields;


use Casper\Exceptions\ValidationFailedException;

class PasswordField extends CharField
{
    private const numberErrorMessage = 'Value must contain a numeric character. ';
    private const symbolErrorMessage = 'Value must contain a symbol. ';
    private const upperCaseErrorMessage = 'Value must contain an upper case. ';
    private const lowerCaseErrorMessage = 'Value must contain a lower case. ';
    /**
     * @var bool
     */
    protected bool $upper;
    /**
     * @var bool
     */
    protected bool $lower;
    /**
     * @var bool
     */
    protected bool $number;
    /**
     * @var bool
     */
    protected bool $symbol;

    /**
     * @param bool $upper
     * @return $this
     */
    public function mustContainUpperCase(bool $upper): self
    {
        $this->upper = $upper;
        return $this;
    }

    /**
     * @param bool $lower
     * @return $this
     */
    public function mustContainLowerCase(bool $lower): self
    {
        $this->lower = $lower;
        return $this;
    }

    /**
     * @param bool $number
     * @return $this
     */
    public function mustContainNumber(bool $number): self
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @param bool $symbol
     * @return $this
     */
    public function mustContainSymbol(bool $symbol): self
    {
        $this->symbol = $symbol;
        return $this;
    }

    /**
     * @return array
     */
    public function asJson(): array
    {
        $res = parent::asJson();
        $res['upper'] = $this->getProperty('upper');
        $res['lower'] = $this->getProperty('lower');
        $res['symbol'] = $this->getProperty('symbol');
        $res['number'] = $this->getProperty('number');

        return $res;
    }

    /**
     * @param string $name
     * @return string
     */
    public function asHtml(string $name=''): string
    {
        $res = parent::asHtml($name);
        $res = str_replace("type='text'", "type='password'", $res);
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
                if(isset($this->upper) && !preg_match('/[A-Z]/', $this->data)) {
                    throw new ValidationFailedException(self::upperCaseErrorMessage);
                }

                if(isset($this->lower) && !preg_match('/[a-z]/', $this->data)) {
                    throw new ValidationFailedException(self::lowerCaseErrorMessage);
                }

                if(isset($this->number) && !preg_match('/\d/', $this->data)) {
                    throw new ValidationFailedException(self::numberErrorMessage);
                }

                if(isset($this->symbol) && !preg_match('/[^a-zA-Z\d]/', $this->data)) {
                    throw new ValidationFailedException(self::symbolErrorMessage);
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