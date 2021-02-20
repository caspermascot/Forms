<?php


namespace Casper\Fields;


use Casper\Exceptions\ValidationFailedException;

class DataListField extends Choices
{
    /**
     * @var bool
     */
    protected bool $allowNewContent = true;

    /**
     * @param bool $allowNewContent
     * @return $this
     */
    public function allowNewContent(bool $allowNewContent): self
    {
        $this->allowNewContent = $allowNewContent;
        return $this;
    }

    /**
     * @return array
     */
    public function asJson(): array
    {
        $res = parent::asJson();
        $res['allowNewContent'] = $this->getProperty('allowNewContent');
        return $res;
    }

    /**
     * @param string $name
     * @return string
     */
    public function asHtml(string $name=''): string
    {
        $res = $this->getParentHtml($name);
        $fieldHtml = $this->getFieldHtml();
        $field = "<input type='text' list='{$name}' {$fieldHtml} >";
        $field .= "<datalist id='{$name}' >";


        foreach ($this->choices as $key => $choice){
            $field .= "<option value='{$choice}'>";
        }

        $field .= " </datalist> ";
        $res = str_replace('htmlField', $field, $res);
        return $res;
    }
    /**
     * @param string $name
     * @return string
     */
    public function getParentHtml(string $name): string
    {
        return parent::asHtml($name);
    }

    public function validate(): ?string
    {
        if($this->allowNewContent === true){

            try {
//                $this->checkEmpty($this->data);
                $this->checkRequired($this->data);
                $this->checkNull($this->data);
                $this->checkBlank($this->data);
                if(!empty($this->data)){
                    $choiceOptions = is_array($this->data) ? $this->data : explode(',',$this->data);
                    $this->setCleanedData(implode(',',$choiceOptions));
                }

                $this->isValid = true;
                return null;

            }catch (ValidationFailedException $validationFailedException){
                $this->isValid = false;
                return $validationFailedException->getMessage();
            }

        }else{
            return parent::validate();
        }

    }
}