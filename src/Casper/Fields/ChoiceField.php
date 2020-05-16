<?php


namespace Casper\Fields;


use Casper\FormUtils;

class ChoiceField extends Choices
{
    /**
     * @var bool
     */
    protected bool $multiple;

    /**
     * @param bool $multiple
     * @return $this
     */
    public function multiple(bool $multiple): self
    {
        $this->multiple = $multiple;
        return $this;
    }

    /**
     * @return array
     */
    public function asJson(): array
    {
        $res = parent::asJson();
        $res['multiple'] = $this->getProperty('multiple');
        return $res;
    }
    /**
     * @param string $name
     * @return string
     */
    protected function asHtml(string $name=''): string
    {
        $res = parent::getParentHtml($name);
        $fieldHtml = $this->getFieldHtml();
        $field = "<select {$fieldHtml} ";
        $field .= empty($this->multiple) ? '' : 'multiple="true" ';
        $field .= " >";

        $data = $this->getChoiceDefault();
        if(FormUtils::isMultiDimensional($this->choices)){
            foreach ($this->choices as $key => $choice){
                $temp = "<option ";
                $temp .= "value = '{$choice}' ";
                $temp .= in_array($choice, $data) ? ' selected="true" ': '';

                $temp .= ">".ucfirst($key)."</option>";
                $field.= $temp;
            }
        }else{
            foreach ($this->choices as $key => $choice){
                $temp = "<option ";
                $temp .= "value = '{$choice}' ";
                $temp .= in_array($choice, $data) ? ' selected="true" ': '';

                $temp .= ">".ucfirst($choice)."</option>";
                $field.= $temp;
            }
        }
        $field .= " </select> ";
        $res = str_replace('htmlField', $field, $res);
        return $res;
    }
    /**
     * @param string $name
     * @return string
     */
    public function getParentHtml(string $name=''): string
    {
        return parent::asHtml($name);
    }
}