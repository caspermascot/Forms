<?php


namespace Casper\Fields;


use Casper\FormUtils;

class CheckBoxField extends ChoiceField
{
    /**
     * @param string $name
     * @return string
     */
    protected function asHtml(string $name=''): string
    {
        $res = parent::getParentHtml($name);
        $field = '';
        $data = $this->getChoiceDefault();
        $count = 0;
        if(FormUtils::isMultiDimensional($this->choices)){
            foreach ($this->choices as $key => $choice){
                $label = ucfirst($key);
                $field.= $this->getChoiceHtmlData($this, $data, $count, $label, $choice, $key,'checkbox', !empty($this->multiple));
                $count+=1;
            }
        }else{
            foreach ($this->choices as $key => $choice){
                $label = ucfirst($choice);
                $field.= $this->getChoiceHtmlData($this, $data, $count, $label, $choice, null,'checkbox', !empty($this->multiple));
                $count+=1;
            }
        }
        $res = str_replace('htmlField', $field, $res);
        return $res;
    }
}