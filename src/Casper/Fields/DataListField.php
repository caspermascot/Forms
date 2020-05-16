<?php


namespace Casper\Fields;


use Casper\FormUtils;

class DataListField extends Choices
{
    /**
     * @param string $name
     * @return string
     */
    protected function asHtml(string $name=''): string
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