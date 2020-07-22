<?php


namespace Casper\Fields;


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
        $res = parent::getParentHtml($name);
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
}