<?php


namespace Casper\Fields;


use Casper\Exceptions\FieldCreateFailedException;
use Casper\Exceptions\ValidationFailedException;

class ListField extends TextField
{
    private const TYPE_STRING = 'string';
    private const TYPE_INTEGER = 'integer';
    private const TYPE_DATE = 'date';

    private const ALL_TYPES = [self::TYPE_STRING, self::TYPE_INTEGER, self::TYPE_DATE];

    /**
     * @var string
     */
    protected string $separator = ',';

    /**
     * @var string
     */
    protected string $type = self::TYPE_STRING;

    /**
     * @var int
     */
    protected int $minValue;

    /**
     * @var int
     */
    protected int $maxValue;

    /**
     * @param int $separator
     * @return $this
     */
    public function separator(int $separator): self
    {
        $this->separator = $separator;
        return $this;
    }

    /**
     * @param string $givenType
     * @return $this
     * @throws FieldCreateFailedException
     */
    public function type(string $type): self
    {
        $givenType = strtolower($type);
        if(!in_array($givenType, self::ALL_TYPES)){
            throw new FieldCreateFailedException(sprintf(
                "%s is not a supported type. Supported types are %s",
                $type, implode(", ", self::ALL_TYPES)));
        }
        $this->type = $givenType;
        return $this;
    }

    /**
     * @param int $minValue
     * @return ListField
     */
    public function minValue(int $minValue): self
    {
        $this->minValue = $minValue;
        return $this;
    }

    /**
     * @param int $maxValue
     * @return ListField
     */
    public function maxValue(int $maxValue): self
    {
        $this->maxValue = $maxValue;
        return $this;
    }

    /**
     * @param string $caller
     * @throws FieldCreateFailedException
     */
    protected function validateFieldCreate(string $caller): void
    {
        parent::validateFieldCreate($caller);

        if(!isset($this->separator)){
            $this->separator = ',';
        }

        if(!isset($this->type)){
            $this->type = 'string';
        }
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

                if ($this->type === self::TYPE_STRING) {
                    $this->validateString();
                } elseif ($this->type === self::TYPE_INTEGER) {
                    $this->validateInteger();
                } elseif ($this->type === self::TYPE_DATE) {
                    $this->validateDate();
                }
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
    private function validateInteger(): void
    {
        $data = explode($this->separator, $this->data);
        foreach ($data as $key => $value) {
            if (!is_numeric($value)) {
                throw new ValidationFailedException(sprintf('Invalid integer given at the %s index', $key));
            }

            if (isset($this->minValue)) {
                $this->checkMinValue($this->minValue, $value);
            }
            if (isset($this->maxValue)) {
                $this->checkMaxValue($this->maxValue, $value);
            }
        }

        $this->setCleanedData((string)$this->data);
    }

    private function validateString(): void
    {
        $this->setCleanedData((string) $this->data);
    }

    /**
     * @throws ValidationFailedException
     */
    private function validateDate(): void
    {
        $data = explode($this->separator, $this->data);
        foreach ($data as $key => $value){
            if(empty(strtotime($value))){
                throw new ValidationFailedException(sprintf('Invalid date given at the %s index', $key));
            }

            if(isset($this->minValue)){
                $this->checkMinDate($this->minValue, $value);
            }
            if(isset($this->maxValue)){
                $this->checkMaxDate($this->maxValue, $value);
            }
        }

        $this->setCleanedData((string) $this->data);
    }
}