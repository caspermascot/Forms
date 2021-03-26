<?php


namespace Casper\Fields;


use Casper\Exceptions\FieldCreateFailedException;
use Casper\Exceptions\ValidationFailedException;
use Casper\FormUtils;

class Choices extends Fields
{
    private const MISSING_CHOICES_MESSAGES = 'Missing required parameter choices, On field %s of form %s ';

    /**
     * @var array
     */
    protected array $choices;

    /**
     * @param array $choices
     * @return $this
     */
    public function choices(array $choices): self
    {
        $this->choices = $choices;
        return $this;
    }

    /**
     * @param string $caller
     * @throws FieldCreateFailedException
     */
    protected function validateFieldCreate(string $caller): void
    {
        parent::validateFieldCreate($caller);
        $name = $this->getProperty('name');

        if(!isset($this->choices)){
            throw new FieldCreateFailedException(sprintf(self::MISSING_CHOICES_MESSAGES
                , $name, $caller));
        }
    }
    /**
     * @return array
     */
    public function asJson(): array
    {
        $res = parent::asJson();
        $res['choices'] = $this->getProperty('choices');
        return $res;
    }

    /**
     * @return array
     */
    protected function getChoiceDefault(): array
    {
        $data = [];
        if(!empty($this->data)){
            if(is_array($this->data)){
                if(FormUtils::isMultiDimensional($data)){
                    $data = array_values($this->data);
                }else{
                    $data = $this->data;
                }
            }else{
                $data = array_values(explode(',',$this->data));
            }
        }
        return $data;
    }

    /**
     * @param Choices $choices
     * @param array $data
     * @param int $count
     * @param string $label
     * @param string $choice
     * @param string $type
     * @param bool $multiple
     * @return string
     */
    protected function getChoiceHtmlData(Choices $choices, array $data, int $count, string $label, string $choice, string $type='radio', bool $multiple=false): string
    {
        $temp = "<input type='{$type}' value='{$choice}' ";

        $temp .= !in_array($choice, $data, true) ? '' : 'checked="true" ';
        $temp .= empty($choices->required) ? '' : 'required="true" ';
        $temp .= empty($choices->hidden) ? '' : 'hidden="true" ';
        $temp .= empty($choices->disabled) ? '' : 'disabled="true" ';

        if(!empty($choices->autoFocus) && $count === 0) {
            $temp .= 'autoFocus="true" ';
        }
        if(!empty($choices->name)){
            $temp .= ($multiple === true) ? " name='{$choices->name}[]' ":" name='{$choices->name}' ";
            $temp .= " id='id_{$choices->name}' ";
        }
        $temp.=" />";
        $temp.= "<label for='{$choices->getProperty('name')}'>{$label}</label> ";
        return $temp;
    }

    /**
     * @return array
     */
    public function asJsonSchema(): array
    {
        $res = parent::asJsonSchema();
        $res['enum'] = $this->choices;

        return $res;
    }

    public function validate(): ?string
    {
        try {
            $this->checkRequired($this->data);
            $this->checkNull($this->data);
            $this->checkBlank($this->data);

            if(isset($this->data)){
                $this->validateChoiceOptions($this->choices, $this->data);
                $this->setCleanedData( $this->data);
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